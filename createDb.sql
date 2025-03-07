create database api;

use api;

drop table if exists comments;
drop table if exists posts;
drop table if exists users;


create table users
(
    id         int auto_increment,
    name       varchar(255) not null,
    email      varchar(255) unique not null,
    password   longtext     not null,
    created_at timestamp default now(),
    updated_at timestamp default null,
    primary key (id)
);

create table posts
(
    id         int auto_increment,
    title      varchar(255) not null,
    content    longtext     not null,
    user_id    int          not null,
    primary key (id),
    foreign key (user_id) references users (id) on delete cascade,
    created_at timestamp default now(),
    updated_at timestamp default null
);

create table comments
(
    id         int auto_increment,
    primary key (id),
    content    longtext not null,
    user_id    int      not null,
    post_id    int      not null,
    foreign key (user_id) references users (id) on delete cascade,
    foreign key (post_id) references posts (id) on delete cascade,
    created_at timestamp default now(),
    updated_at timestamp default null

);



insert into users (name, email, password)
values ('John Doe', 'john@example.com', 'password123'),
       ('Jane Smith', 'jane@example.com', 'password123'),
       ('Alice Brown', 'alice@example.com', 'password123'),
       ('Bob White', 'bob@example.com', 'password123'),
       ('Charlie Black', 'charlie@example.com', 'password123'),
       ('David Green', 'david@example.com', 'password123'),
       ('Eva Adams', 'eva@example.com', 'password123'),
       ('Frank Wilson', 'frank@example.com', 'password123'),
       ('Grace Hall', 'grace@example.com', 'password123'),
       ('Henry King', 'henry@example.com', 'password123');


insert into posts (title, content, user_id)
values ('First Post', 'This is the first post content', 1),
       ('Second Post', 'This is the second post content', 1),
       ('Another Post', 'Some interesting content here', 2),
       ('New Insights', 'Sharing some new thoughts', 2),
       ('My Journey', 'Documenting my experiences', 3),
       ('Coding Tips', 'Best practices for coding', 3),
       ('Tech News', 'Latest updates in tech', 4),
       ('Travel Blog', 'Exploring the world', 4),
       ('Healthy Living', 'Tips for a healthy life', 5),
       ('Book Reviews', 'Thoughts on recent reads', 5);


insert into comments (content, user_id, post_id)
values ('Great post!', 2, 1),
       ('I totally agree!', 3, 1),
       ('Interesting perspective!', 4, 2),
       ('Nice insights.', 5, 2),
       ('Love this content!', 6, 3),
       ('Very informative.', 7, 3),
       ('Well written!', 8, 4),
       ('I learned something new.', 9, 4),
       ('Awesome tips!', 10, 5),
       ('This was helpful!', 1, 5);
