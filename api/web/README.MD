
访问接口：
182.254.217.147:8381
目前可以访问数据：
用户表：user 只可以查看
GET  v2/users 访问所有用户，
GET  v2/users/ID 访问单个用户，

帖子表：threads 可以增删查
GET V2/threads 查看所有帖子信息
GET V2/threads/ID 查看单个帖子信息

POST v2/threads  加post参数 content,image_path,user_id

DELETE v2/threads/ID 删除单个帖子，不支持多贴删除

由于图片原因目前不支持更改，如要更改可以先删除后再发帖

评论贴表：posts 可以增删改查

GET V2/posts 查看所有帖子信息
GET V2/posts/ID 查看单个帖子信息

POST v2/posts  加post参数 content,thread_id,user_id

DELETE v2/posts/ID 删除单个评论，不支持多条删除

PATCH v2/posts/ID 更新用户评论 可更新内容：content



