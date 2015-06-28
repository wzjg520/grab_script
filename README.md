采集大众点评店铺数据脚本

说明：
1.data 目录用于存放自动生成的采集数据文件，detail_url_cityname.txt文件用于存放店铺网址
query_cityname.json 文件用于存放列表地址，req_url_cityname.txt用于脚本执行错误时，恢复现场。

2.html 目录用于存放没个店铺的html源代码

3.log 目录用于存放日志。本脚本日志需要重定向至log目录

5.grab_data.php 是数据采集脚本

6.push_data.php 是解析脚本，用于分析html后生成数据存入数据库

操作方法，

php grab_data.php beijing 采集店铺html源代码，后按目录存入html目录中。

php push_data.php beijing 解析html后存入数据库。

数据表格式请阅读源码后建表
