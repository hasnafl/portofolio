import os
import sys
from django.conf import settings
import numpy as np
import tflearn
import random
import pickle
import json
import nltk
from Sastrawi.Stemmer.StemmerFactory import StemmerFactory
from flask import Flask, render_template, request, redirect, url_for
import string
from urllib.request import urlopen
import warnings
import stanfordnlp
import nltk
from nltk.corpus import stopwords
from nltk.util import ngrams
from collections import Counter
from IPython.display import display
import mysql.connector
from mysql.connector import Error
from datetime import datetime

warnings.filterwarnings('ignore')
mydb = mysql.connector.connect(
host="localhost",
user="root",
password="",
database="chatbot"
)

mycursor = mydb.cursor()

app = Flask(__name__, template_folder='template')
factory = StemmerFactory()
stemmer = factory.create_stemmer()
training_file = ('C:/xampp/htdocs/chatbot/training_data')
data = pickle.load(open(training_file, "rb"))

words = data['words']
classes = data['classes']
train_x = data['train_x']
train_y = data['train_y']

pengetahuan = ('C:/xampp/htdocs/chatbot/pengetahuan.json')

with open(pengetahuan) as json_data:
    pengetahuan = json.load(json_data)
    
# load deepneuralnet
input_h = tflearn.input_data(shape=(None, len(train_x[0])))
h2 = tflearn.fully_connected(input_h, 9)
h3 = tflearn.fully_connected(h2, 18)
h4 = tflearn.fully_connected(h3, 18)
h5 = tflearn.fully_connected(h4, 9)

output_h = tflearn.fully_connected(h5, len(train_y[0]), activation='softmax')
output_h_reg = tflearn.regression(output_h)
# Define model dan setup tensorboard
model = tflearn.DNN(output_h_reg, tensorboard_dir='tflearn_logs')

def clean_up_sentence(sentence):
    sentence_words = nltk.word_tokenize(sentence)
    sentence_words = [stemmer.stem(word.lower()) for word in sentence_words]
    return sentence_words
def bow(sentence, words, show_details=False):
    sentence_words = clean_up_sentence(sentence)
    print(sentence_words)
    bag = [0]*len(words)
    for s in sentence_words:
        for i,w in enumerate(words):
            if w == s:
                bag[i] = 1
                if show_details:
                    print("test : %s" % w)
    return np.array(bag)

# load model yang disimpan
load_model = ('C:/xampp/htdocs/chatbot/./model.tflearn')
model.load(load_model)

context = {}

ERROR_THRESHOLD = 0.25

def classify(sentence):
    results = model.predict([bow(sentence, words)])[0]
    print(results)
    results = [[i, r] for i, r in enumerate(results) if r > ERROR_THRESHOLD]
    print(results)
    results.sort(key=lambda x: x[1], reverse=True)
    return_list = []
    for r in results:
        return_list.append((classes[r[0]], r[1]))

    return return_list
def find_topic(text):
    nlp = stanfordnlp.Pipeline(lang='id', processors='tokenize,pos')
    grammar = "NP: {<NOUN|PROPN>+ <ADJ>*}"
    parser = nltk.RegexpParser(grammar)
    print(text)
    doc = nlp(text.lower())
    # create word and POS tag pair
    pairs = []
    for sentence in doc.sentences:
        tagged = []
        for word in sentence.words:
            tagged.append((word.text, word.upos))
        pairs.append(tagged)
    keywords = []
    for sentence in pairs:
        parse_tree = parser.parse(sentence)
        for subtree in parse_tree.subtrees():
            if subtree.label() == 'NP' and len(subtree.leaves()) >= 1:  # only consider bigram
                words = [item[0] for item in subtree.leaves()]
                keywords.append(' '.join(words))
    print(pairs)
    return_list = keywords[:4]
    return return_list
def concatenate_list_data(list):
    result= ''
    for element in list:
        result += str(element) + " "
    return result
def response(sentence, userid='hasnafl', show_details=False):
    # print("first sentence : ", sentence)
    # sentence = find_topic(sentence)
    # print("sec sentence : ", sentence)
    # sentence = concatenate_list_data(sentence)
    # print("concate sentence : ", sentence)
    results = classify(sentence)
    if results:
        while results:
            for i in pengetahuan['pengetahuan']:
                if i['tag'] == results[0][0]:
                    if 'context_set' in i:
                        if show_details:
                            print('context:', i['context_set'])
                        context[userid] = i['context_set']
                        output_var = str(random.choice(i['responses']))
                        return output_var

                    if not 'context_filter' in i or \
                        (userid in context and 'context_filter' in i and i['context_filter'] == context[userid]):
                        if show_details:
                            print('tag:', i['tag'])
                        context_filter = i.get('context_filter', '')
                        output_var = str(random.choice(i['responses']))
                        return output_var

            results.pop(0)
print(classify('apa yang dimaksud dengan bea cukai?'))
print(response('apa yang dimaksud dengan bea cukai?'))
#define app routes
@app.route("/display")
def display_image():
    filename = 'profile.png'
    print('display_image filename: ' + filename)
    return redirect(url_for('static', filename='img/' + filename), code=301)
@app.route("/")
def index():
    return render_template("index4.html")
@app.route("/verification")
def ver():
    message = request.args.get('msg')
    message = message.split('/')
    user_name = message[0]
    user_company_name = message[1]
    now = datetime.now()
    # dt_string = now.strftime("%d-%m-%Y %H:%M:%S")
    sql = "INSERT INTO user (`user_name`, `user_company_name`, `datetime`) VALUES (%s, %s, %s)"
    val = (user_name, user_company_name, now)
    mycursor.execute(sql, val)
    mydb.commit()
    
    mycursor.execute("SELECT * FROM verification")
    myresult = mycursor.fetchall()
    verified = 0
    for x in myresult:
        acc_id = x[0]
        name = x[1]
        company_name = x[2]
        if (user_name.casefold()==name.casefold() and user_company_name.casefold() in company_name.casefold()):
            verified = 1
            pass
    return str(verified)
@app.route("/get")
#function for the bot response
def get_bot_response():
    mycursor.execute("SELECT user_id FROM user ORDER BY user_id DESC LIMIT 1")
    myresult = mycursor.fetchone()
    lastrow_id = myresult[0]
    userText = request.args.get('msg')
    iterasi = request.args.get('i')
    bot_response = str(response(userText))
    sql = "INSERT INTO `conversation` (`user_id`, `user_response`, `bot_response`) VALUES (%s, %s, %s)"
    val = (lastrow_id, userText, bot_response)
    mycursor.execute(sql, val)
    mydb.commit()
    if(bot_response=="untuk pertanyaan mendetail silahkan hubungi customer service kami"):
        mycursor.execute("SELECT `conversation_id` FROM `conversation` ORDER BY `conversation_id` DESC LIMIT 1")
        myresult = mycursor.fetchone()
        conversation_id = myresult[0]
        sql = "INSERT INTO `another_question` (`user_id`, `conversation_id`, `question`) VALUES (%s, %s, %s)"
        val = (lastrow_id, conversation_id, userText)
        mycursor.execute(sql, val)
        mydb.commit()
    return str(response(userText))

@app.after_request
def add_header(response):
    """
    Add headers to both force latest IE rendering engine or Chrome Frame,
    and also to cache the rendered page for 10 minutes.
    """
    response.headers['X-UA-Compatible'] = 'IE=Edge,chrome=1'
    response.headers['Cache-Control'] = 'public, max-age=0'
    return response

if __name__ == "__main__":
    app.run(debug=True)