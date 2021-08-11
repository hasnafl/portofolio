#------------------------------------------import dependency------------------------------------------------
import numpy as np
import argparse, imutils, math, optparse
import cv2, sys, os, re, logging
import six.moves.urllib as urllib
import tensorflow as tf
import matplotlib.pyplot as plt
import matplotlib as mplt
from scipy import ndimage
from skimage import io
from skimage.transform import rotate
from skimage import measure, morphology
from skimage.color import label2rgb
from skimage.measure import regionprops
from skimage.filters import threshold_local
from pyimagesearch.transform import four_point_transform
from PIL import Image
from PIL import ImageFilter
from collections import defaultdict
from io import StringIO
from utils import label_map_util
from utils import visualization_utils as vis_util
from carddetector import id_card_detection_image
from tensorflow import keras
mplt.rcParams.update({'figure.max_open_warning': 0})
# get_ipython().run_line_magic('matplotlib', 'inline')
sys.path.append("..")
#-----------------------------------------------------------------------------------------------------------

#-----fungsi untuk menghitung tingkat kecerahan gambar-----
def calculate_brightness(image):
    greyscale_image = image.convert('L')
    histogram = greyscale_image.histogram()
    pixels = sum(histogram)
    brightness = scale = len(histogram)
    for index in range(0, scale):
        ratio = histogram[index] / pixels
        brightness += ratio * (-scale + index)
    return 1 if brightness == 255 else brightness / scale
#----------------------------------------------------------
#-------------fungsi untuk menajamkan gambar---------------
def img_sharpening(image):
    sharpened1 = image.filter(ImageFilter.SHARPEN);
    image = sharpened1
    return image
#----------------------------------------------------------
#------------------fungsi untuk resize gambar--------------------
def img_resizer(image):
    #ambil ukuran gambar 95% dari ukuran aslinya
    scale_percent = 95
    width = int(image.shape[1] * scale_percent / 100)
    height = int(image.shape[0] * scale_percent / 100)
    dim = (width, height)
    #resize gambar
    image = cv2.resize(image, dim, interpolation = cv2.INTER_AREA)
    return image
#-----------------------------------------------------------------

class all_function:
    #---------------------------------------Inisialisasi----------------------------------------------------
    def __init__(self, i, image, image_path, output_path, output_path_result, output_preversion_path):
        self.image_path = image_path
        self.i = i
        self.output_path = output_path
        self.image = image
        self.output_preversion_path = output_preversion_path
    #---------------------------------------akhir inisialisasi----------------------------------------------
    #---------------------------------------Normalisasi posisi gambar---------------------------------------
    def rotate_img(self):
        try:
            #--------------Baca Gambar--------------
            img_before = cv2.imread(self.image_path)
            #--------------perubahan gambar ke skala abu dan deteksi garis tepi-------------------------
            img_gray = cv2.cvtColor(img_before, cv2.COLOR_BGR2GRAY)
            img_edges = cv2.Canny(img_gray, 200, 200, apertureSize=3)
            lines = cv2.HoughLinesP(img_edges, 1, math.pi / 180.0, 100, minLineLength=300, maxLineGap=25)
            #-------------------------------------------------------------------------------------------
            #------------------Perhitungan angle------------------------
            angles = []
            for x1, y1, x2, y2 in lines[0]:
                cv2.line(img_before, (x1, y1), (x2, y2), (255, 0, 0), 3)
                angle = math.degrees(math.atan2(y2 - y1, x2 - x1))
                angles.append(angle)
            median_angle = np.median(angles)
            print("Angle is {}".format(median_angle))
            #-----------------------------------------------------------
            #--------------------------Rotasi gambar--------------------------------
            if (median_angle<-45 or (median_angle<90 and median_angle>=87)):
                img_rotated = ndimage.rotate(img_before, (median_angle+180), cval=0)
            else:
                img_rotated = ndimage.rotate(img_before, median_angle, cval=0)
            print('image deskew success')
            #-----------------------------------------------------------------------
            #---------------------------Simpan Gambar-------------------------------
            cv2.imwrite(self.output_path+'/ktp_'+str(self.i)+'.jpg', img_rotated)
            #-----------------------------------------------------------------------
            image = img_rotated
            return image
        except:
            """ simpan gambar original jika line tepi tidak terdeteksi """
            #-----------------------Simpan Gambar Original--------------------------
            cv2.imwrite(self.output_path+'/ktp_'+str(self.i)+'.jpg', img_before)
            image = img_before
            print("angle isn't detected at deskew process")
            return image
    #----------------------------------------Akhir normalisasi posisi gambar---------------------------------
    #-------------------------------Ekstrak tandatangan menjadi gambar preversion----------------------------
    #-------------------------Fungsi untuk membuat gambar preversion------------------------
    def extract_signature(self):
        try:
            #--------------------------------proses ke 1------------------------------------
            #baca gambar
            image = self.image
            #resize gambar
            image = img_resizer(image)
            #path untuk menyimpan gambar hasil resize dan sharpening
            path = self.output_preversion_path
            #rubah gambar ke pillow format
            image = Image.fromarray(image)
            #menajamkan gambar
            image = img_sharpening(image)
            #menyimpan gambar pada path sebelumnya
            image.save(path)
            #menghitung kecerahan gambar dalam (%)
            image_brightness = calculate_brightness(image)
            print(image_brightness," %")
        except Exception as e:
            logging.exception(e)
            print('error pada proses ke 1 preversion')
            #------------------------------Akhir proses ke 1---------------------------------
        try:
            #---------------------------------proses ke 2------------------------------------
            #penyesuaian tingkat ketajaman gambar sesuai dengan kecerahan gambar
            if(image_brightness<0.47):
                sharp = 50
            elif(image_brightness>=0.47) and (image_brightness<0.55):
                sharp = 125
            elif(image_brightness>=0.55) and (image_brightness<0.6):
                sharp = 130
            elif(image_brightness>=0.6 and image_brightness<0.67):
                sharp = 135
            elif(image_brightness>=0.67):
                sharp = 150
            #membaca ulang gambar pada path sebelumnya
            img = cv2.imread(path,0)
            #threshold gambar dengan ketajaman sesuai dengan kecerahannya
            img = cv2.threshold(img, sharp, 200, cv2.THRESH_BINARY)[1]
            blobs = img > img.mean()
            blobs_labels = measure.label(blobs, background=1)
            image_label_overlay = label2rgb(blobs_labels, image=img, bg_label=0)
        except Exception as e:
            logging.exception(e)
            print('error pada proses ke 2 preversion')
            #------------------------------Akhir proses ke 2---------------------------------
        try:
            #---------------------------------proses ke 3------------------------------------
            #inisialisasi variabel yang akan digunakan
            the_biggest_component = 0
            total_area = 0
            counter = 0
            average = 0.0
            #perhitungan area yang akan di-scan dan proses mencari objek terbesar yang berhasil di-scan
            for region in regionprops(blobs_labels):
                if (region.area > 10):
                    total_area = total_area + region.area
                    counter = counter + 1
                # take regions with large enough areas
                if (region.area >= 250):
                    if (region.area > the_biggest_component):
                        the_biggest_component = region.area
            average = (total_area/counter)
            #penentuan nilai pixel default untuk patokan objek terbesar
            a4_constant = 300
            #menghilangkan pixel yang lebih kecil dari pada a4_constant
            b = morphology.remove_small_objects(blobs_labels, a4_constant)
            #menyimpan gambar yang masih berwarna-preversion
            plt.imsave(path, b)
            return b
            #------------------------------Akhir proses ke 3---------------------------------
        except Exception as e:
            logging.exception(e)
            print("error pada proses ke 3 preversion")
    #----------------------------------Akhir pemrosesan gambar preversion------------------------------------
    #------------------------Pemrosesan gambar preversion ke gambar hitam putih------------------------------
    #--------------------------fungsi finalisasi tanda tangan-----------------------
    def finalization(self):
        try:
            #atur path ke direktori gambar preversion
            path = self.output_preversion_path
            #baca gambar preversion
            img = cv2.imread(path, 0)
            #memproses gambar menjadi hitam putih
            img = cv2.threshold(img, 90, 255, cv2.THRESH_BINARY_INV | cv2.THRESH_OTSU)[1]
            return img
        except Exception as e:
            logging.exception(e)
            print("error on finalization")
    #---------------------------akhir fungsi finalisasi-----------------------------
    #----------------------------Akhir pemrosesan preversion ke hitam putih----------------------------------

#-----------------------------------fungsi untuk pemrosesan utama----------------------------------------
def image_landscape(image):
    (im_width, im_height) = image.size
    if (im_height>im_width):
        image  = self.image.transpose(Image.ROTATE_90)
    return image    
def load_image_into_numpy_array(image):
    (im_width, im_height) = image.size
    img_data = np.array(image.getdata()).reshape((im_height, im_width, 3)).astype(np.uint8)
    return img_data
#-----------------------------------akhir fungsi pemrosesan utama----------------------------------------
class signature_extraction:
    #---------------------------------------Inisialisasi-----------------------------------------------------
    def __init__(self, model_name, test_image_dir, file_path):
        self.model_name = model_name
        self.test_image_dir = test_image_dir
        self.file_path = file_path
    #--------------------------------------------------------------------------------------------------------
    #----------------------------------------pemrosesan utama KTP--------------------------------------------
    def main_process(self):
        #folder model
        MODEL_NAME = self.model_name
        #path menuju ke graph untuk deteksi objek
        PATH_TO_CKPT = MODEL_NAME + '/frozen_inference_graph.pb'
        #path menuju ke file object label
        PATH_TO_LABELS = os.path.join('data', 'object-detection.pbtxt')
        #nomor kelas yang ada pada objek
        NUM_CLASSES = 1
        #load model ke tensorflow memory
        detection_graph = tf.Graph()
        with detection_graph.as_default():
          od_graph_def = tf.GraphDef()
          with tf.gfile.GFile(PATH_TO_CKPT, 'rb') as fid:
            serialized_graph = fid.read()
            od_graph_def.ParseFromString(serialized_graph)
            tf.import_graph_def(od_graph_def, name='')
        #load objek label
        label_map = label_map_util.load_labelmap(PATH_TO_LABELS)
        categories = label_map_util.convert_label_map_to_categories(label_map, max_num_classes=NUM_CLASSES, use_display_name=True)
        category_index = label_map_util.create_category_index(categories)
        #path menuju images dir
        PATH_TO_TEST_IMAGES_DIR = self.test_image_dir
        #TEST_IMAGE_PATHS = [ os.path.join(PATH_TO_TEST_IMAGES_DIR, 'ktp{}.jpeg'.format(i)) for i in range(1, 27) ]
        file_path = self.file_path
        filenames = os.listdir(file_path)
        filenames = [ f for f in filenames if os.path.isfile(os.path.join(file_path, f)) and (re.match('[^\_]*\.jpg$', f) or re.match('[^\_]*\.jpeg$', f)) ]
        filenames.sort(key=lambda f: int(re.sub('\D', '', f)))
        TEST_IMAGE_PATHS = []
        for numb in range(len(filenames)):
            print(filenames[numb])
            path = os.path.join(PATH_TO_TEST_IMAGES_DIR, filenames[numb])
            TEST_IMAGE_PATHS.append(path)
        # ukuran gambar untuk ditampilkan dalam inch
        IMAGE_SIZE = (10, 6)                
        #-----------------proses pendeteksian variabel untuk box deteksi objek-------------------------------
        with detection_graph.as_default():
            with tf.Session(graph=detection_graph) as sess:
                # Definite input and output Tensors for detection_graph
                image_tensor = detection_graph.get_tensor_by_name('image_tensor:0')
                # Each box represents a part of the image where a particular object was detected.
                detection_boxes = detection_graph.get_tensor_by_name('detection_boxes:0')
                # Each score represent how level of confidence for each of the objects.
                # Score is shown on the result image, together with the class label.
                detection_scores = detection_graph.get_tensor_by_name('detection_scores:0')
                detection_classes = detection_graph.get_tensor_by_name('detection_classes:0')
                num_detections = detection_graph.get_tensor_by_name('num_detections:0')
        #--------------------------akhir deteksi variabel untuk box deteksi objek-----------------------------
                #inisialisasi variabel untuk penanda urutan
                i = 1
                #perulangan untuk memproses gambar-gambar yang ada pada direktori test gambar
                #print(TEST_IMAGE_PATHS)
                for image_path in TEST_IMAGE_PATHS:
                    #----------------------------proses deteksi tanda tangan----------------------------------
                    #path menuju direktori gambar test
                    input_path = 'D:/signature/object-detection/flask-master/signature_detection/test_images/'
                    #path menuju gambar yang telah di deskew
                    output_path = 'D:/signature/object-detection/flask-master/signature_detection/test_images/rotated'
                    #path untuk menyimpan hasil tanda tangan akhir
                    output_path_result = 'D:/signature/object-detection/flask-master/signature_detection/static/result/sign_ktp_'+str(i)+'.png'
                    #output path untuk menyimpan gambar preversion
                    output_preversion_path = 'D:/signature/object-detection/flask-master/signature_detection/result2/preversion/prev_'+str(i)+'.png'
                    try:
                        #baca gambar dari path test gambar
                        image = Image.open(image_path)
                    except:
                        im_name, im_format = image_path.split('.')
                        im_format = 'jpg'
                        image_path = im_name+'.'+im_format
                        image = Image.open(image_path)
                    #objek 1
                    #o1 = all_function(i, image, image_path, output_path, output_path_result, output_preversion_path)
                    #membuat seluruh gambar berorientasi landscape
                    image = image_landscape(image)
                    #image = o1.image_landscape()
                    #memperbaharui gambar yang ada pada direktori test gambar yaitu menjadi landscape
                    image.save(image_path)
                    #objek 1
                    o1 = all_function(i, image, image_path, output_path, output_path_result, output_preversion_path)
                    #proses penormalan gambar yang miring
                    #image = rotate_img(image_path, i, output_path)
                    image = o1.rotate_img()
                    #proses crop id card menggunakan id card detector
                    image = id_card_detection_image.crop_card(image, input_path, i)
                    #menyamaratakan seluruh ukuran gambar
                    height, width = image.size
                    if(height>width):
                        image = image.resize((756,550))
                    elif(width>height):
                        image = image.resize((550,756))
                    #objek 3
                    #o3 = all_function(i, image, image_path, output_path, output_path_result, output_preversion_path)
                    #convert gambar ke numpy array
                    image_np = load_image_into_numpy_array(image)
                    #image_np = o3.load_image_into_numpy_array()
                    #menyimpan id card atau ktp yang sudah dicrop ke crop direktori
                    cv2.imwrite(input_path+'crop/ktp_'+str(i)+'.jpg', image_np)
                    #Expand dimensions since the model expects images to have shape: [1, None, None, 3]
                    image_np_expanded = np.expand_dims(image_np, axis=0)
                    #pendeteksian tanda tangan
                    (boxes, scores, classes, num) = sess.run(
                      [detection_boxes, detection_scores, detection_classes, num_detections],
                      feed_dict={image_tensor: image_np_expanded})
                    #visualisasi untuk hasil pendeteksian
                    vis_util.visualize_boxes_and_labels_on_image_array(
                      image_np,
                      np.squeeze(boxes),
                      np.squeeze(classes).astype(np.int32),
                      np.squeeze(scores),
                      category_index,
                      use_normalized_coordinates=True,
                      line_thickness=8,
                      min_score_thresh=0.20) #akurasi minimal untuk menampilkan tanda tangan
                    #pencatatan koordinat dari box yang mengelilingi tanda tangan
                    coordinates = vis_util.return_coordinates(
                                image_np,
                                np.squeeze(boxes),
                                np.squeeze(classes).astype(np.int32),
                                np.squeeze(scores),
                                category_index,
                                use_normalized_coordinates=True,
                                line_thickness=8,
                                min_score_thresh=0.20) #akurasi minimal untuk menampilkan koordinat box
                    #menampilkan hasil deteksi objek dari gambar KTP yang di scan
                    fig = plt.figure(figsize=IMAGE_SIZE)
                    plt.imshow(image_np)
                    plt.cla()
                    #--------------------------------akhir proses deteksi------------------------------------
                    #---------------------pengolahan tanda tangan yang telah terdeteksi----------------------
                    try:
                        #koordinat tanda tangan yang terdeteksi pada KTP
                        (y1, y2, x1, x2, acc) = coordinates[0]
                        height = y2-y1
                        width = x2-x1
                        #pemotongan gambar sesuai dengan koordinat tanda tangan sehingga hanya gambar ttd saja yang didapat
                        image = Image.open(input_path+'crop/ktp_'+str(i)+'.jpg')
                        img_crop = image.crop((x1, y1, x2, y2))
                        #-------------------mencari posisi tanda tangan pada 4 bagian gambar-------------------
                        #baca input gambar dari direktori crop
                        im_split =  cv2.imread(input_path+"crop/ktp_"+str(i)+".jpg")
                        #pembulatan ukuran gambar
                        imgheight_split=im_split.shape[0]
                        imgwidth_split=im_split.shape[1]
                        if (imgheight_split%2!=0):
                            imgheight_split = imgheight_split-1
                        if (imgwidth_split%2!=0):
                            imgwidth_split = imgwidth_split-1
                        #split gambar menjadi 4 bagian
                        y1_split = 0
                        M_split = imgheight_split//2
                        N_split = imgwidth_split//2
                        #inisialisasi nomor untuk urutan bagian gambar
                        num_split = 1
                        #proses pendeteksian koordinat pada setiap bagian gambar
                        koordinat_split = []
                        for y_split in range(0,imgheight_split,M_split):
                            for x_split in range(0, imgwidth_split, N_split):
                                y1_split = y_split + M_split
                                x1_split = x_split + N_split
                                tiles = im_split[y_split:y_split+M_split, x_split:x_split+N_split]

                                cv2.rectangle(im_split, (x_split, y_split), (x1_split, y1_split), (0, 255, 0))
                                image_crop_split = cv2.rectangle(im_split, (x_split, y_split), (x1_split, y1_split), (0, 255, 0))
                                coor_split = x_split,y_split,x1_split,y1_split
                                num_split = num_split+1
                                koordinat_split.append(coor_split)
                        jum_split = len(koordinat_split)
                        #proses perbandingan antara koordinat posisi tanda tangan dengan koordinat bagian gambar
                        for a_split in range(jum_split):
                            x1_split, y1_split, x2_split, y2_split = koordinat_split[a_split]
                            #kondisi untuk mengecek posisi tanda tangan ada di bagian gambar berapa
                            if(((x1+y1)>(x1_split+y1_split)) and (x2+y2)<(x2_split+y2_split)):
                                #pemberian flag sesuai dengan di bagian berapa tanda tangan tersebut berada
                                flag_num = a_split+1
                                pass
                        #--------------akhir pencarian posisi tanda tangan pada 4 bagian gambar-----------------
                        #menampilkan posisi ttd ada pada bagian mana
                        print('FLAG NUMBER = ', flag_num)
                        #menampilkan akurasi tanda tangan
                        print('Signature Accuration = ', acc, ' %')
                        #pengkondisian untuk rotasi tanda tangan sesuai dengan posisi ttd pada 4 bagian gambar
                        #flag nomor 1 merupakan posisi kiri atas KTP
                        if (flag_num == 1):
                            #rotasi sebanyak 180 derajat. rotasi ini searah jarum jam
                            img_crop = img_crop.rotate(180, expand=True)
                        #flag nomor 2 merupakan posisi kiri bawah KTP
                        if (flag_num == 2):
                            #rotasi sebanyak 270 derajat. rotasi ini searah jarum jam
                            img_crop = img_crop.rotate(270, expand=True)
                        #flag nomor 3 merupakan posisi kanan atas KTP
                        if (flag_num == 3):
                            #rotasi sebanyak 90 derajat. tanda - menandakan rotasi ini searah jarum jam
                            img_crop = img_crop.rotate(-90, expand=True)
                        #penyimpanan gambar tanda tangan yang sudah disesuaikan posisinya.
                        img_crop.save('D:/signature/object-detection/flask-master/signature_detection/result2/sign_ktp_'+str(i)+'.png')             
                    except:
                        print("signature isn't detected")
                        print('------------------------------------------------------------------------------------------')
                        pass
                    #------------------------------akhir pengolahan tanda tangan-----------------------------
                    #-----------proses perubahan foto tandatangan menjadi tanda tangan hitam putih-----------
                    try:
                        #convert pillow image ke opencv
                        image = cv2.cvtColor(np.array(img_crop), cv2.COLOR_RGB2BGR)
                        #objek 2
                        o2 = all_function(i, image, image_path, output_path, output_path_result, output_preversion_path)
                        #penggunaan fungsi untuk merubah gambar ke preversion
                        #prev_image = extract_signature(output_path_preversion, image)
                        prev_image = o2.extract_signature()
                        print('signature extraction done.')
                        #penggunaan fungsi untuk merubah preversion ke versi hitam putih
                        #img = finalization(output_path_preversion)
                        img = o2.finalization()
                        #penyimpanan hasil tanda tangan akhir
                        cv2.imwrite('D:/signature/object-detection/flask-master/signature_detection/static/result/sign_ktp_'+str(i)+'.png', img)
                        print('signature finalization done.')
                        print('-------------------------------------------------------------------------------------------')
                    except:
                        print("signature extraction error")
                        print("Oops!", sys.exc_info()[0], "occurred.")
                        print('-------------------------------------------------------------------------------------------')
                        pass
                    #----------------------------akhir proses tanda tangan hitam putih------------------------
                    #inisialisasi untuk count seiring perulangan file yang di test
                    i = i+1
    #------------------------------------akhir pemrosesan utama KTP------------------------------------------



