import sys
sys.path
import pandas as pd
import mysql.connector
from sqlalchemy import create_engine
from sqlalchemy.dialects.mysql import insert
import string
import re
import nltk
from sqlalchemy.sql import *
from sqlalchemy import *
from sqlalchemy.orm import sessionmaker
from Sastrawi.StopWordRemover.StopWordRemoverFactory import StopWordRemoverFactory, StopWordRemover, ArrayDictionary
from flask import Flask, request, jsonify, json
#from sklearn.externals import joblib
import traceback

app = Flask(__name__)

# df=pd.read_csv(r'//home//py//datatraining.csv',encoding='latin1')
engine = create_engine('mysql+mysqlconnector://root:BHv$VH7GL%J4frstH3@localhost/hscode_server')
df = pd.read_sql('SELECT * FROM datatraining_', engine)
df['desc'] = df['content']

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
    #total=0
    
    for item in eval_items:
        true_pred=item[0]
        machine_pred=set(item[1])
        
        for cat in true_pred:
            if cat in machine_pred:
                correct+=1
                break
    
    
    accuracy=correct/float(len(eval_items))
    return accuracy

from sklearn.metrics import precision_recall_fscore_support
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LogisticRegression
from sklearn.feature_extraction.text import CountVectorizer,TfidfVectorizer

import numpy as np
import logging

logging.basicConfig(format='%(asctime)s : %(levelname)s : %(message)s', level=logging.INFO)

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

    # GET TOP K PREDICTIONS BY PROB - note these are just index
    best_n = np.argsort(probs, axis=1)[:,-k:]
    
    # GET CATEGORY OF PREDICTIONS
    preds=[[model.classes_[predicted_cat] for predicted_cat in prediction] for prediction in best_n]
    
    preds=[ item[::-1] for item in preds]
    
    return preds
   
    
def train_model(df,field="desc",feature_rep="binary",top_k=3):
    
    logging.info("Starting model training...")
    
    # GET A TRAIN TEST SPLIT (set seed for consistent results)
    training_data, testing_data = train_test_split(df, random_state = 2000)

    # GET LABELS
    Y_train=training_data['hscode'].values
    Y_test=testing_data['hscode'].values
     
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
def waktu():
    import time
    a=time.localtime()
    hr=(a.tm_hour)-1
    mn=a.tm_min
    sc=a.tm_sec
    return ('{}:{}:{}'.format(hr,mn,sc))
@app.route("/trainhscode", methods=['POST', 'GET'])
def train():
    if loaded_model:
        try:
            #s = request.args.get('s')
            #s = urllib.parse.unquote_plus(s)
            #s = [str(s)]
            start_time=waktu()
            feature_reps=['counts','tfidf']
            fields=['desc']
            top_ks=[3]
            results=[]
            for field in fields:
                for feature_rep in feature_reps:
                    for top_k in top_ks:
                        model,transformer,acc,mrr_at_k=train_model(df,field=field,feature_rep=feature_rep,top_k=top_k)
                        results.append([field,feature_rep,top_k,acc,mrr_at_k])
            import pickle
            model_path="/home/py/modelhscode_exmp.pkl"
            transformer_path="/home/py/transformerhscode_exmp.pkl"
            pickle.dump(model,open(model_path, 'wb'))
            pickle.dump(transformer,open(transformer_path,'wb'))
            end_time=waktu()
            metadata = MetaData(bind=engine)
            log = Table('log', metadata, autoload=True)
            conn = engine.connect()
            metadata = MetaData()
            Session = sessionmaker(bind=conn)
            session = Session()
            # insert
            i = insert(log)
            i = i.values({"start_time": start_time, "end_time": end_time})
            session.execute(i)
            session.commit()
            return jsonify({'start_time': start_time, 'end_time': end_time})
        except:
            return jsonify({'trace': traceback.format_exc()})
    else:
        print ('Train the model first')
        return ('No model here to use')
    
if __name__ == "__main__":
    try:
        port = int(sys.argv[1]) # This is for a command-line input
    except:
        port = 8000
    import pickle
    model_paths='/home/py/goodmodel.pkl'
    loaded_model = pickle.load(open(model_paths, 'rb'))
    print('model loaded')
    import socket
    hostname = socket.gethostname()
    IPAddr = socket.gethostbyname(hostname)
    app.run(port=port, debug=True, host=IPAddr)