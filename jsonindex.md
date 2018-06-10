第一个数字 jsontype第二数字 confirmtype 错误类型0 成功 1  “不存在”2 数据库连接错误      

 登陆 1 adminname  password  完成 

加书 2 名称保持一致         

删书 3 选中的的docId1        

加实体书 4    callNum version place (isOnShelf)

 查询书目普通检索 5 keywords            （还给我 recordnumber总共条目数）   完成

 查询书目高级检索 6 名称保持一致            （还给我 recordnumber总共条目数）  doctype: all(0), book(1), article,(2) jornal(3) //  

跳转页数 7  页数 (已经没有用了把） 

加读者 8 （name是中文名） 完成 

检索读者 9  （同上）完成 usertype 0  读者 1 管理者         完成 

加管理者 10    完成 

检索管理者 11 （没参数） 

借阅记录 12  recordtype: all(0), user(1) book(2） 

修改书 13 0123 doctype 加书+bookid 修

改读者 管理员 14   

删除读者 管理员 15  userid 

审核 16 只给你16 还给我 name username  title  begindate    requestID  json数组外面用   requestrecords 

审核 17 只给你 17,requestID , isagreed(同意是1， 拒绝是0），还给我  操作是否成功issuccessed（成功是1， 拒绝是0（） 