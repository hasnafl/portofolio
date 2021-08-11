import mysql.connector
import pandas as pd
mydb = mysql.connector.connect(
  host="10.1.1.110",
  user="hasna",
  passwd="H4snA%2020$",
  database="db_hasna"
)
mycursor = mydb.cursor()

mycursor.execute("SELECT request_id FROM preadvice_header")
request_id = mycursor.fetchall()
df = pd.DataFrame(request_id, columns=mycursor.column_names)
df = df.groupby(['request_id']).size().reset_index(name='count')
is_1 = df['count']>1
df_1 = df[is_1]
x = len(df_1)
for i in range(x):
#     print(df['count'][i])
      sql = "UPDATE preadvice_header SET dup_flag = %s WHERE request_id = %s"
      val = (1, df['request_id'][i])
      mycursor.execute(sql, val)
      mydb.commit()
      print('update ',df['request_id'][i])
print("---------------------------------------------------------------------")
mycursor.execute("SELECT service_number FROM preadvice_detail")
service_number = mycursor.fetchall()
df = pd.DataFrame(service_number, columns=mycursor.column_names)
df = df.groupby(['service_number']).size().reset_index(name='count')
is_1 = df['count']>1
df_1 = df[is_1]
x = len(df_1)
for i in range(x):
#     print(df['count'][i])
      sql = "UPDATE preadvice_detail SET dup = %s WHERE service_number = %s"
      val = (1, df['service_number'][i])
      mycursor.execute(sql, val)
      mydb.commit()
      print('update ',df['service_number'][i])