import os
os.environ['PYSPARK_SUBMIT_ARGS'] = '--jars /home/jars/mysql.jar pyspark-shell'

import pyspark
sqlContext = pyspark.SQLContext(pyspark.SparkContext())

dataframe_mysql = sqlContext.read.format("jdbc").options(url="jdbc:mysql://10.1.1.110/db_hasna",driver = "com.mysql.jdbc.Driver",dbtable = "preadvice_header",user="hasna", password="H4snA%2020$").load()

allColumns = dataframe_mysql.request_id
# print(allColumns)
import sys
from pyspark.sql import functions as f
from pyspark.sql import window as w
windowSpec = w.Window.partitionBy(allColumns).rowsBetween(-sys.maxsize, sys.maxsize)

df_new = dataframe_mysql.withColumn('dup', (f.count('request_id').over(windowSpec) > 1).cast('int'))
df_new = df_new.orderBy('id')

dup = df_new.select("dup").rdd.flatMap(lambda x: x).collect()


# import mysql.connector
# mydb = mysql.connector.connect(
#   host="10.1.1.110",
#   user="hasna",
#   passwd="H4snA%2020$",
#   database="db_hasna"
# )
# mycursor = mydb.cursor()
# mycursor.execute("SELECT request_id FROM preadvice_header")
# request_id = mycursor.fetchall()
# for i in range(len(dup)):
#     if (dup[i]==1):
#         sql = "UPDATE preadvice_header SET dup_flag = %s WHERE request_id = %s"
#         val = (dup[i], request_id[i][0])
#         mycursor.execute(sql, val)
#         mydb.commit()
#         print('success update request_id : ', request_id[i][0])


# detail_mysql = sqlContext.read.format("jdbc").options(url="jdbc:mysql://10.1.1.110/db_hasna",driver = "com.mysql.jdbc.Driver",dbtable = "preadvice_detail",user="hasna", password="H4snA%2020$").load()

# allColumns = detail_mysql.service_number
# # print(allColumns)
# import sys
# from pyspark.sql import functions as f
# from pyspark.sql import window as w
# windowSpec = w.Window.partitionBy(allColumns).rowsBetween(-sys.maxsize, sys.maxsize)

# df_new = detail_mysql.withColumn('dup', (f.count('service_number').over(windowSpec) > 1).cast('int'))
# df_new = df_new.orderBy('id')

# dup = df_new.select("dup").rdd.flatMap(lambda x: x).collect()


# mycursor.execute("SELECT service_number FROM preadvice_detail")
# service_number = mycursor.fetchall()
# for i in range(len(dup)):
# #     dup[i] = str(dup[i])
#     if (dup[i]==1):
# #         print(dup[i])
#         sql = "UPDATE preadvice_detail SET dup = %s WHERE service_number = %s"
#         val = (dup[i], service_number[i][0])
#         mycursor.execute(sql, val)
#         mydb.commit()
#         print('success update service_number : ', service_number[i][0])




