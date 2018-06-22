# Document



## Api Reference



## class





### SystemFrame



#### Member Functions

| Function Name      | Return Type      |
| ------------------ | ---------------- |
| instance()         | SystemFrame      |
| userData()         | UserData         |
| adminData()        | AdminData        |
| borrowRecordData() | borrowRecordData |
| docData()          | DocData          |
| log_info($info)    | void             |
| initServer()       | void             |
|                    |                  |











***
### Document



#### Member Functions

​	

| Function Name | Return Type     |
| ------------- | --------------- |
| getDocId      | int             |
| getTitle      | string          |
| getAuthor     | array of string |
| getPublisher  | string          |
| getSource     | string          |
| getUrls       | array of string |
| subjects      | array of string |
| description   | string          |
| language      | string          |
| docType       | string          |



***

### Book (extends Document)



####Member Functions



| Function Name | Return Type     |
| ------------- | --------------- |
| getDocId      | int             |
| getPublicYear | string          |
| getTitle      | string          |
| getAuthor     | array of string |
| getPublisher  | string          |
| getSource     | string          |
| getUrls       | array of string |
| getBookNum    | int             |
| getBooks      | array of book   |
| subjects      | array of string |
| getISBN       | array of string |
| format        | string          |
| description   | string          |
| language      | string          |
| docType       | string          |



***



### Journal (extends Document)



#### Member Functions

| Function Name   | Return Type     |
| --------------- | --------------- |
| getDocId        | int             |
| getCreationYear | string          |
| getTitle        | string          |
| getAuthor       | array of string |
| getPublisher    | string          |
| getSource       | string          |
| getISSN         | string          |
| getUrls         | array of string |
| subjects        | array of string |
| language        | string          |
| description     | string          |
| format          | string          |
| docType         | string          |
|                 |                 |



***

### Article (extends Document)

#### Member Functions

| Function Name | Return Type     |
| ------------- | --------------- |
| getDocId      | int             |
| getPublicYear | string          |
| getTitle      | string          |
| getAuthor     | array of string |
| getPublisher  | string          |
| getSource     | string          |
| description   | string          |
| getUrls       | array of string |
| partOf        | string          |
| subjects      | array of string |
| getISSN       | string          |
| getdoi        | string          |
| getISBN       | array of string |
| docType       | string          |











***

### RealBook (extends Book)

#### Member Functions

| Function Name           | Return Type |
| ----------------------- | ----------- |
| getBookId               | int         |
| getBook                 | Book&       |
| getIsbn                 | string      |
| getCallNumber  //索书号 | string      |
| getVersion              | int         |
| getPublicYear           | string      |
| isOnShelf               | bool        |
| getPlace                | string      |
| language                | string      |





***

### DocData

####Member Functions

| Function Name             | Return Type       |
| ------------------------- | ----------------- |
| query                     | array of Document |
| addDocument(Document&)    | void              |
| addRealBook(Document&)    | void              |
| deleteDocument(Document&) | void              |
| getDocument(docID)        | Document&         |
| getRealBook(bookID)       | RealBook&         |
|                           |                   |
|                           |                   |

***

### Account (Abstract Class)

#### Member Function

| Function Name            | Return Type |
| ------------------------ | ----------- |
| getUsername              | string      |
| getPassword              | string      |
| getName                  | string      |
| setName                  | void        |
| setPasswd(newPasswd)     | void        |
| setUsername(newUsername) | void        |











***

### User (extends Account)

#### Member Function

| Function Name            | Return Type           |
| ------------------------ | --------------------- |
| getUsername              | string                |
| getPassword              | string                |
| getUid //编号            | string                |
| getName                  | string                |
| setPasswd(newPasswd)     | void                  |
| setUsername(newUsername) | void                  |
| setUid(newUid)           | void                  |
| setName(newName)         | void                  |
| borrowList               | array of BorrowRecord |







***

### Admin (extends Account)

#### Member Functions

#### 

| Function Name            | Return Type |
| ------------------------ | ----------- |
| getUsername              | string      |
| getPassword              | string      |
| getName                  | string      |
| setPasswd(newPasswd)     | void        |
| setUsername(newUsername) | void        |
| setName(newName)         | void        |



***

### AccountData (Abstract Class)

#### Member Functions

#### 

| Function Name                                                | Return Type     |
| ------------------------------------------------------------ | --------------- |
| userList                                                     | array of Person |
| queryFromName(name)                                          | array of Person |
| queryFromUsername(username)                                  | Person&         |
| queryFromId(Id)                                              | Person&         |
| addAccount(&\$Account)                                       | void            |
| deleteUser(\$uuid, \$deleteType) //type 为1时uuid为usename,为2时uuid为为uid，,默认为1 | void            |









***

### UserData （extends AccountData)



#### Member Functions

| Function Name                                                | Return Type   |
| ------------------------------------------------------------ | ------------- |
| usersList                                                    | array of User |
| queryFromName(name)                                          | array of User |
| queryFromUsername(username)                                  | User&         |
| queryFromUid(uid)                                            | User&         |
| addAccount(&\$Account)                                       | void          |
| deleteUser(\$uuid, \$deleteType) //type 为1时uuid为usename,为2时uuid为为uid，,默认为1 | void          |
|                                                              |               |
|                                                              |               |



***

### AdminData (extands AccountData)

#### Member Functions

| Function Name                                                | Return Type    |
| ------------------------------------------------------------ | -------------- |
| adminsList                                                   | array of Admin |
| queryFromName(name)                                          | array of Admin |
| queryFromUsername(username)                                  | Admin&         |
| queryFromUid(uid)                                            | Admin&         |
| addAccount(&\$Account)                                       | void           |
| deleteUser(\$uuid, \$deleteType) //type 为1时uuid为username, 默认为1 | void           |
|                                                              |                |
|                                                              |                |





***

### BorrowRecord

#### Member Properties

| Property Name | Type      |
| ------------- | --------- |
| user          | &User     |
| recordId      | int       |
| realBook      | &realBook |
| document      | &document |
| beginDate     | string    |
| dueDate       | string    |
| returnDate    | string    |
| isReturned    | bool      |
| requestTime   | string    |
| answerTime    | string    |
| isAnswered    | bool      |
| isAllowed     | bool      |





***

### BorrowRecordData

#### Member Functions

| addBorrowRequest    | void |
| ------------------- | ---- |
| getBorrowRecordList | void |
|                     |      |

