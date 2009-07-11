CREATE TABLE Class
(
        course_id CHAR(3),
        class_id CHAR(5),
        title VARCHAR(500),
        name VARCHAR(500)
)
;

CREATE TABLE ClassID
(
       main_course_id CHAR(3),
       main_class_id CHAR(5),
       this_course_id CHAR(3),
       this_class_id CHAR(5)
)
;
