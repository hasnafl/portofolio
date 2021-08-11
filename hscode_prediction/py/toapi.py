import sys
sys.path
import re
import pandas as pd
import string
import nltk
from Sastrawi.StopWordRemover.StopWordRemoverFactory import StopWordRemoverFactory, StopWordRemover, ArrayDictionary
from flask import Flask, request, jsonify, json
#from sklearn.externals import joblib
import traceback

app = Flask(__name__)


#this assumes one json item per line in json file
# df = pd.read_csv(r'/home/py/trainingsetnew.csv')
df = pd.read_csv(r'trainingsetnew.csv')
# df = pd.read_csv(r'C:/Users/IT DEV/Documents/Classification/trainingsetnew.csv')

#just the description
df['desc'] = df['Kind Of Goods (After Cleansing)']

def _reciprocal_rank(true_labels: list, machine_preds: list):
    """Compute the reciprocal rank at cutoff k"""
    
    # add index to list only if machine predicted label exists in true labels
    tp_pos_list = [(idx + 1) for idx, r in enumerate(machine_preds) if r in true_labels]

    rr = 0
    if len(tp_pos_list) > 0:
        # for RR we need position of first correct item
        first_pos_list = tp_pos_list[0]
        
        # rr = 1/rank
        rr = 1 / float(first_pos_list)

    return rr

def compute_mrr_at_k(items:list):
    """Compute the MRR (average RR) at cutoff k"""
    rr_total = 0
    
    for item in items:   
        rr_at_k = _reciprocal_rank(item[0],item[1])
        rr_total = rr_total + rr_at_k
        mrr = rr_total / 1/float(len(items))

    return mrr

def collect_preds(Y_test,Y_preds):
    """Collect all predictions and ground truth"""
    
    pred_gold_list=[[[Y_test[idx]],pred] for idx,pred in enumerate(Y_preds)]
    return pred_gold_list
          
def compute_accuracy(eval_items:list):
    correct=0
   # total=0
    
    for item in eval_items:
        true_pred=item[0]
        machine_pred=set(item[1])
        
        for cat in true_pred:
            if cat in machine_pred:
                correct+=1
                break
    
    
    accuracy=correct/float(len(eval_items))
    return accuracy

# logging.basicConfig(format='%(asctime)s : %(levelname)s : %(message)s', level=logging.INFO)

def extract_features(df,field,training_data,testing_data,type="binary"):
    """Extract features using different methods"""
    
    logging.info("Extracting features and creating vocabulary...")
    
    if "binary" in type:
        
        # BINARY FEATURE REPRESENTATION
        cv= CountVectorizer(binary=True, max_df=0.95)
        cv.fit_transform(training_data[field].values)
        
        train_feature_set=cv.transform(training_data[field].values)
        test_feature_set=cv.transform(testing_data[field].values)
        
        return train_feature_set,test_feature_set,cv
  
    elif "counts" in type:
        
        # COUNT BASED FEATURE REPRESENTATION
        cv= CountVectorizer(binary=False, max_df=0.95)
        cv.fit_transform(training_data[field].values)
        
        train_feature_set=cv.transform(training_data[field].values)
        test_feature_set=cv.transform(testing_data[field].values)
        
        return train_feature_set,test_feature_set,cv
    
    else:    
        
        # TF-IDF BASED FEATURE REPRESENTATION
        tfidf_vectorizer=TfidfVectorizer(use_idf=True, max_df=0.95)
        tfidf_vectorizer.fit_transform(training_data[field].values)
        
        train_feature_set=tfidf_vectorizer.transform(training_data[field].values)
        test_feature_set=tfidf_vectorizer.transform(testing_data[field].values)
        
        return train_feature_set,test_feature_set,tfidf_vectorizer

def get_top_k_predictions(model,X_test,k):
    
    # get probabilities instead of predicted labels, since we want to collect top 3
    probs = model.predict_proba(X_test)
    #conf = model.predict_proba(X_test)[:,-k:]
    #conf = str(conf).strip('[]')
    cl_probs = probs[0]
    # GET TOP K PREDICTIONS BY PROB - note these are just index
    best_n = np.argsort(probs, axis=1)[:,-k:]
    angka = best_n[0]
    ang = angka[0]
    conf = cl_probs[ang]
    conf = round(conf, 6)
    # conf = str(conf)
    
    # GET CATEGORY OF PREDICTIONS
    preds=[[model.classes_[predicted_cat] for predicted_cat in prediction] for prediction in best_n]
    
    preds=[ item[::-1] for item in preds]
    preds = str(preds).strip('[]')
    
    return preds, conf
      
def train_model(df,field="desc",feature_rep="binary",top_k=3):
    
    logging.info("Starting model training...")
    
    # GET A TRAIN TEST SPLIT (set seed for consistent results)
    training_data, testing_data = train_test_split(df, random_state = 2000)

    # GET LABELS
    Y_train=training_data['HS Code (After Cleansing)'].values
    Y_test=testing_data['HS Code (After Cleansing)'].values
     
    # GET FEATURES
    X_train,X_test,feature_transformer=extract_features(df,field,training_data,testing_data,type=feature_rep)

    # INIT LOGISTIC REGRESSION CLASSIFIER
    logging.info("Training a Logistic Regression Model...")
    scikit_log_reg = LogisticRegression(verbose=1, solver='liblinear',random_state=0, C=5, penalty='l2',max_iter=1000)
    model=scikit_log_reg.fit(X_train,Y_train)

    # GET TOP K PREDICTIONS
    preds=get_top_k_predictions(model,X_test,top_k)
    
    # GET PREDICTED VALUES AND GROUND TRUTH INTO A LIST OF LISTS - for ease of evaluation
    eval_items=collect_preds(Y_test,preds)
    
    # GET EVALUATION NUMBERS ON TEST SET -- HOW DID WE DO?
    logging.info("Starting evaluation...")
    accuracy=compute_accuracy(eval_items)
    mrr_at_k=compute_mrr_at_k(eval_items)
    
    logging.info("Done training and evaluation.")
    
    return model,feature_transformer,accuracy,mrr_at_k

import pickle
from sklearn.metrics import precision_recall_fscore_support
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LogisticRegression
from sklearn.feature_extraction.text import CountVectorizer,TfidfVectorizer
import urllib.parse
import urllib
import numpy as np
import logging
@app.route("/predict", methods=['POST', 'GET'])
def predict():
    if loaded_model:
        try:
            #konten = request.json
            #x = konten['x']
            #data = json.loads(request.data)
            #x = data.get("x")
            #query = pd.DataFrame(json_)
            # print(request.data)
            d = request.data
            data = json.loads(d)
            x = data.get('kind_of_goods')
            # print(data.get('test'))
            # x = request.args.get('x')
            #x = urllib.parse.unquote_plus(x)
            x = [str(x)]
            test_features=loaded_transformer.transform(x)
            a, j = get_top_k_predictions(loaded_model,test_features,1)
            #a = str(a).strip('[]')
            #test_features=loaded_transformerhscode.transform([a])
            #b = get_top_k_predictions(loaded_modelhscode,test_features,1)
            #b = str(b).strip('[]')
            return jsonify({'kind_of_goods': x[0],'prediction': a,'confidence': j})
        except:

            return jsonify({'trace': traceback.format_exc()})
    else:
        print ('Train the model first')
        return ('No model here to use')

#c = ["Sempurna Alis Listrik Finishing Penghapus Alis Alat Cukur Tanpa Rasa Sakit Perawatan Wajah MinI Pemotong Rambut"]
#print(c)

#print(a)

#print(b)

if __name__ == "__main__":
    try:
        port = int(sys.argv[1]) # This is for a command-line input
    except:
        port = 9090
    # model_path='/home/py/modelhscode_10exmp.pkl'
    model_path='modelhscode_10exmp.pkl'
    # model_path='C:/Users/IT DEV/Documents/Classification/modelkogtohscode.pkl'
    # transformer_path='/home/py/transformerhscode_10exmp.pkl'
    transformer_path='transformerhscode_10exmp.pkl' 
    # transformer_path='C:/Users/IT DEV/Documents/Classification/transformerkogtohscode.pkl' 
    loaded_model = pickle.load(open(model_path, 'rb'))
    print('model loaded')
    loaded_transformer = pickle.load(open(transformer_path, 'rb'))
    print('transformer loaded')
    import socket
    hostname = socket.gethostname()
    IPAddr = socket.gethostbyname(hostname)
    app.run(port=port, debug=True, host=IPAddr)