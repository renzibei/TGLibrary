第一个数字 jsontype第二数字

返回值包jsontype为0 confirmtype 错误类型0 成功 1  “不存在”2 数据库连接错误      

 登陆 1 adminname  password  完成 

加书 2 名称保持一致         

删书 3 选中的的docId1        

加实体书 4    callNum version place (isOnShelf)

 查询书目普通检索 5 keywords            （还给我 recordnumber总共条目数）   完成

 查询书目高级检索 6 名称保持一致            （还给我 recordnumber总共条目数）  doctype: all(0), book(1), article,(2) jornal(3) //  

跳转页数 7  页数 (已经没有用了把） 

加读者 8 （name是中文名） 完成 

检索读者 9  （同上）完成 usertype 0  读者 1 管理者     （回复时'accounts'键对应着检索结果的数组）    完成 

加管理者 10    完成 

//检索管理者 11  （回复时'accounts'键对应着检索结果的数组）   （没参数） 

借阅记录 12  recordtype: all(0), user(1) book(2） 

修改书 13 0123 doctype 加书+bookid 修

改读者 管理员 14   

删除读者 管理员 15  userid 

审核 16 只给你16 还给我 name username  title  begindate    requestID  json数组外面用   requestrecords 

审核 17 只给你 17,requestID , isagreed(同意是1， 拒绝是0），还给我  操作是否成功issuccessed（成功是1， 拒绝是0（） 





------

Version 2

json协议：

1、每一个都要含有jsontype来区分；



 //2、confirmvalue的约定：     

0：成功，并且返回相应需要的返回值hi  

1：信息没有找到的错误（具体：登陆界面是用户名密码错误，查询读者，查询书目等等对应的是查不到任何东西    

2：对应的是数据库那里发生错误（几乎不会用到，取决于你那里数据库是否抛出异常）    

3：重复的错误（具体：增加读者用户名，书籍标题等等你认为必须要判定重复的东西）// 



3、jsontype 的详细区分 右端为给你的参量

登陆 1 adminname  password   完成

加虚拟书 2 名称与虚拟书信息条目保持一致 完成     

删书 3 选中的的docId1      完成 

加实体书 4    callNum version place (isOnShelf)，我倾向于把这个功能做到虚拟书信息页面，有一个添加实体书的功能，所以为了添加实体书需要通过修改书目的路径进去，因此有可能同时修改虚拟书和实体书，因此可能同时先后发给你两个json包（2、4）或（13、4）。同时估计没时间做删实体书和修改实体书了。也没什么意义，对于大作业微不足道的分数边际效应递减完全应该忽略不计）完成 

查询书目普通检索 5 keywords            （还给我 documents总共条目数）   完成

查询书目高级检索 6 名称保持一致       （还给我 documents总共条目数）   完成  

//跳转页数 7  页数  （已经没用了目前开来 ，直接用滚动条滚动表格就行）

加读者 8 （name是中文名）与读者需要的信息保持一致     完成

检索读者 9  （同上）完成 usertype 0读者    1管理者         完成

加管理者 10    完成

//检索管理者 11 （没参数）已经于9完成

借阅记录 12  recordtype: 书目名称(1) 书目ID(2）读者姓名（3） 读者ID（4） 还给我以documents为关键字的所有记录的数组。 真正输入的信息保存在information 里   注意数字都是字符串。  完成

修改书 13 bookid+全部修改之后的信息 

完成改读者 管理员 14  usertype 完成

删除读者 管理员 15  只给 userid 和usertype 完成 usertype 读者0 管理员1 完成

查询待审核记录 16 只给你16 还给我 name username  title  begindate  requestID  json数组外面用   documents标记  完成

审核操作 17 只给你 17,requestID , isagreed(同意是1， 拒绝是0），还给我  操作是否成功，这里使用confirmvalue 0成功 2失败（与约定保持一致） 完成 