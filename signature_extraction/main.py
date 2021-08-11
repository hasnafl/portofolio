import os, copy, re, shutil
import cv2
from app import app
import urllib.request
from tensorflow import keras
from flask import Flask, flash, request, redirect, url_for, render_template
from werkzeug.utils import secure_filename


ALLOWED_EXTENSIONS = set(['png', 'jpg', 'jpeg', 'gif'])

def allowed_file(filename):
	return '.' in filename and filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS

@app.route('/')
def upload_form():
	return render_template('upload.html')

@app.route('/', methods=['POST'])
def upload_image():
	if 'file' not in request.files:
		flash('No file part')
		return redirect(request.url)
	file = request.files['file']
	if file.filename == '':
		flash('No image selected for uploading')
		return redirect(request.url)
	if file and allowed_file(file.filename):
		filename = secure_filename(file.filename)
		file.save(os.path.join(app.config['UPLOAD_FOLDER'], filename))
		#print('upload_image filename: ' + filename)
		# original_img = cv2.imread('static/uploads/'+filename)
		# clone_img = copy.copy(original_img)
		# cv2.imwrite('static/uploads/copy_'+filename, clone_img)
		import logging
		from signature_extraction_oo import signature_extraction

		#path untuk model
		model_name = 'sign_model'
		#path untuk test images
		test_image_dir = 'static/uploads'
		#path lengkap menuju direktori test images
		file_path = 'D:/signature/object-detection/flask-master/signature_detection/static/uploads'
		#main objects
		main = signature_extraction(model_name, test_image_dir, file_path)
		try:
			result = main.main_process()
		except Exception as e:
			logging.exception(e)
		file_path = 'D:/signature/object-detection/flask-master/signature_detection/static/result'
		filename = os.listdir(file_path)
		# filename = [ f for f in filename if os.path.isfile(os.path.join(file_path,f)) and re.match('[^\_]*\.png$', f) ]
		filename = filename[0]
		print(filename)
		flash('Signature successfully processed and displayed')
		folder = 'D:/signature/object-detection/flask-master/signature_detection/static/uploads'
		for files in os.listdir(folder):
			file_pth = os.path.join(folder, files)
			try:
				if os.path.isfile(file_pth) or os.path.islink(file_pth):
					os.unlink(file_pth)
				elif os.path.isdir(file_pth):
					shutil.rmtree(file_pth)
			except Exception as e:
				print('Failed to delete %s. Reason: %s' % (file_pth, e))
		#---------------------------------menghapus isi directory---------------------------------
		folder = 'D:/signature/object-detection/flask-master/signature_detection/test_images/rotated'
		for files in os.listdir(folder):
			file_pth = os.path.join(folder, files)
			try:
				if os.path.isfile(file_pth) or os.path.islink(file_pth):
					os.unlink(file_pth)
				elif os.path.isdir(file_pth):
					shutil.rmtree(file_pth)
			except Exception as e:
				print('Failed to delete %s. Reason: %s' % (file_pth, e))
		folder = 'D:/signature/object-detection/flask-master/signature_detection/test_images/crop'
		for files in os.listdir(folder):
			file_pth = os.path.join(folder, files)
			try:
				if os.path.isfile(file_pth) or os.path.islink(file_pth):
					os.unlink(file_pth)
				elif os.path.isdir(file_pth):
					shutil.rmtree(file_pth)
			except Exception as e:
				print('Failed to delete %s. Reason: %s' % (file_pth, e))
		folder = 'D:/signature/object-detection/flask-master/signature_detection/result2'
		for files in os.listdir(folder):
			file_pth = os.path.join(folder, files)
			try:
				if os.path.isfile(file_pth) or os.path.islink(file_pth):
					os.unlink(file_pth)
				elif os.path.isdir(file_pth):
					shutil.rmtree(file_pth)
			except Exception as e:
				print('Failed to delete %s. Reason: %s' % (file_pth, e))
		os.mkdir('D:/signature/object-detection/flask-master/signature_detection/result2/preversion')
		return render_template('upload.html', filename=filename)
	else:
		flash('Allowed image types are -> png, jpg, jpeg, gif')
		return redirect(request.url)

@app.route('/display/<filename>')
def display_image(filename):
	print('display_image filename: ' + filename)
	return redirect(url_for('static', filename='result/' + filename), code=301)
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