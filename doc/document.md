# Document



## class



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
| getISBN       | string          |
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
| getCreationDate | string          |
| getTitle        | string          |
| getAuthor       | array of string |
| getPublisher    | string          |
| getSource       | string          |
| getISSN         | string          |
| getUrls         | array of string |
| getBookNum      | int             |
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
| getISBN       | string          |
| docType       | string          |











***

### RealBook (extends Book)

#### Member Functions

| Function Name           | Return Type     |
| ----------------------- | --------------- |
| getBookId               | int             |
| getBook                 | Book&           |
| getIsbn                 | array of string |
| getCallNumber  //索书号 | string          |
| getVersion              | int             |
| getPublicYear           | string          |
| isOnShelf               | bool            |
| getPlace                | string          |
| language                | string          |
| docType                 | string          |





***

### BookData

####Member Functions

| Function Name             | Return Type       |
| ------------------------- | ----------------- |
| query                     | array of Document |
| addDocument(Document&)    | void              |
| addRealBook(Document&)    | void              |
| deleteDocument(Document&) | void              |
| getDocment(docID)         | Document&         |
| getRealBook(bookID)       | RealBook&         |
|                           |                   |
|                           |                   |

***

### Person (Abstract Class)

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

### User (extends Person)

#### Member Function

| Function Name            | Return Type |
| ------------------------ | ----------- |
| getUsername              | string      |
| getPassword              | string      |
| getUid //编号            | string      |
| getName                  | string      |
| setPasswd(newPasswd)     | void        |
| setUsername(newUsername) | void        |
| setUid(newUid)           | void        |
| setName(newName)         | void        |







***

### Admin (extends Person)

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

### PersonData (interface)

#### Member Functions

#### 

| Function Name                                                | Return Type     |
| ------------------------------------------------------------ | --------------- |
| userList                                                     | array of Person |
| queryFromName(name)                                          | array of Person |
| queryFromUsername(username)                                  | Person&         |
| queryFromUid(uid)                                            | Person&         |
| addPerson(&\$Person)                                         | void            |
| deleteUser(\$uuid, \$deleteType) //type 为2时uuid为为uid，为1时uuid为usename,默认为1 | void            |









***

### UserData （implements PersonData)



#### Member Functions

| Function Name                                                | Return Type   |
| ------------------------------------------------------------ | ------------- |
| usersList                                                    | array of User |
| queryFromName(name)                                          | array of User |
| queryFromUsername(username)                                  | User&         |
| queryFromUid(uid)                                            | User&         |
| addUser(&\$User)                                             | void          |
| deleteUser(\$uuid, \$deleteType) //type 为1时uuid为为uid，为2时uuid为usename | void          |
|                                                              |               |
|                                                              |               |



***

### AdminData (implements PersonData)

#### Member Functions

| Function Name                                                | Return Type    |
| ------------------------------------------------------------ | -------------- |
| adminsList                                                   | array of Admin |
| queryFromName(name)                                          | array of Admin |
| queryFromUsername(username)                                  | Admin&         |
| queryFromUid(uid)                                            | User&          |
| addUser(&\$User)                                             | void           |
| deleteUser(\$uuid, \$deleteType) //type 为1时uuid为username, 默认为1. | void           |
|                                                              |                |
|                                                              |                |