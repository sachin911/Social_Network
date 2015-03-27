drop database concert;
create database concert;
use concert;
create table user(user_id varchar(100),
uname varchar(50),
password varchar(50),
dob date,
address varchar(100),
phnum numeric(12),
email_addr varchar(100),
join_date datetime,
trust_score int check(0<=trust_score<=10),
primary key (user_id));

create table follows(follower varchar(100),
following varchar(100) check (following <> follower),
fdate date,
primary key(follower,following),
foreign key (follower) references user(user_id) on delete cascade,
foreign key (following) references user(user_id)on delete cascade);

create table genre(gen_id int auto_increment,
gen_name varchar(100),
subcat varchar(100),
primary key (gen_id)); 

create table band( bid int auto_increment,
band_name varchar(40) unique,
bdate date,
admin_id varchar(40),
primary key (bid),
foreign key(admin_id) references user(user_id) on delete cascade);


create table fan(fid int auto_increment,
user_id varchar(100),
fdate date,
primary key (fid),
foreign key (user_id) references user(user_id) on delete cascade);

create table fan_band(fid int,
bid int,
primary key(fid,bid),
foreign key(fid) references fan(fid) on delete cascade,
foreign key(bid) references band(bid)on delete cascade
);

create table fan_genre(fid int,
gen_id int,
primary key(fid,gen_id),
foreign key(fid) references fan(fid) on delete cascade,
foreign key(gen_id) references genre(gen_id)on delete cascade);

create table band_genre(bid int,
gen_id int,
primary key(bid,gen_id),
foreign key(bid) references band(bid) on delete cascade,
foreign key(gen_id) references genre(gen_id)on delete cascade);


create table venue(vid int auto_increment,
place varchar(100),
city varchar(100),
addressurl varchar(100),
primary key(vid));

create table concert(cid int auto_increment,
vid int,
admin_id int(10),
description varchar(1000),
vdate date,
primary key(cid),
foreign key(vid) references venue(vid) on delete cascade,
foreign key(admin_id) references fan(fid) on delete cascade);

create table band_concert(bid int,
cid int,
primary key(bid,cid),
foreign key(bid) references band(bid) on delete cascade,
foreign key(cid) references concert(cid) on delete cascade);

create table posts(pid int auto_increment,
user_id varchar(100),
description varchar(1000),
pdate datetime,
like_count int,
primary key(pid),
foreign key(user_id) references user(user_id) on delete cascade);

create table concert_posts(pid int,
cid int,
primary key(pid,cid),
foreign key(pid) references posts(pid) on delete cascade,
foreign key(cid) references concert(cid) on delete cascade);

create table band_posts(pid int,
bid int,
primary key(pid,bid),
foreign key(pid) references posts(pid) on delete cascade,
foreign key(bid) references band(bid) on delete cascade);

create table user_posts(user_id varchar(100),
pid int,
primary key(user_id,pid),
foreign key(user_id) references user(user_id) on delete cascade,
foreign key(pid) references posts(pid) on delete cascade);

create table vote(user_id varchar(100),
cid int,
rating int(10) check (0<=rating<=5),
vdate datetime,
review varchar(1000),
foreign key(user_id) references user(user_id) on delete cascade,
foreign key(cid) references concert(cid) on delete cascade);

create table participation(user_id varchar(100),
status varchar(10) check(status in ('yes','no','maybe')),
cid int,
part_time datetime,
foreign key(user_id) references user(user_id) on delete cascade,
foreign key(cid) references concert(cid) on delete cascade);


insert into User(user_id,uname,password,dob,address,phnum,join_date,email_addr,trust_score) values('sm1111','Steve M','password','1980-11-22','1111,53rd street, 8th av,brooklyn,ny-11992',1111111111,now(),'a@b.com',6);
insert into User(user_id,uname,password,dob,address,phnum,join_date,email_addr,trust_score) values('ar2222','Apple Red','password','1990-04-12','1221,23rd street, 2th av,manhattan,ny-12292',22222222222,now(),'b@c.com',6);
insert into User(user_id,uname,password,dob,address,phnum,join_date,email_addr,trust_score) values('fs3333','Flin Stone','password','1970-01-05','1131,8th street, 6th av,brooklyn,ny-19892',3333333333,now(),'c@d.com',6);
insert into User(user_id,uname,password,dob,address,phnum,join_date,email_addr,trust_score) values('gr4444','George Rod','password','1975-09-18','211,5th street,Queens,ny-10092',4444444444,now(),'d@b.com',6);
insert into User(user_id,uname,password,dob,address,phnum,join_date,email_addr,trust_score) values('sm5555','Steve M','password','1991-04-07','26,79th street, 12th av,Manhattan,ny-23992',5555555555,now(),'e@y.com',6);

insert into follows(follower,following,fdate) values('fs3333','sm1111','2010-10-19');
insert into follows(follower,following,fdate) values('sm1111','ar2222','2005-07-29');
insert into follows(follower,following,fdate) values('gr4444','sm1111','1999-11-30');
insert into follows(follower,following,fdate) values('sm1111','gr4444','2013-06-10');
insert into follows(follower,following,fdate) values('ar2222','sm1111','2013-06-10');

insert into Genre(gen_name,subcat) values('Jazz','Free Jazz');
insert into Genre(gen_name,subcat) values('Rock','Classic rock');
insert into Genre(gen_name,subcat) values('Rock','Metal');
insert into Genre(gen_name,subcat) values('Country','Americana');
insert into Genre(gen_name,subcat) values('Pop','Cool pop');
insert into Genre(gen_name,subcat) values('Hip hop','R n B');

insert into Band(band_name,bdate,admin_id) values('Metallica','1990-12-30','sm1111');
insert into Band(band_name,bdate,admin_id) values('Iron Maiden','2000-07-10','ar2222');
insert into Band(band_name,bdate,admin_id) values('The Beatles','1985-01-30','fs3333');
insert into Band(band_name,bdate,admin_id) values('The black eyed peas','2003-05-24','gr4444');
insert into Band(band_name,bdate,admin_id) values('Eagles','1965-09-02','sm1111');

insert into Band_genre(bid,gen_id)values(1,3);
insert into Band_genre(bid,gen_id)values(2,3);
insert into Band_genre(bid,gen_id)values(3,5);
insert into Band_genre(bid,gen_id)values(4,6);
insert into Band_genre(bid,gen_id)values(5,4);

insert into Fan(user_id,fdate) values('sm1111','2011-09-10');
insert into Fan(user_id,fdate) values('ar2222','2010-07-18');
insert into Fan(user_id,fdate) values('fs3333','2003-10-18');
insert into Fan(user_id,fdate) values('gr4444','2005-11-28');
insert into Fan(user_id,fdate) values('sm5555','2014-02-27');

insert into Fan_band(fid,bid) values(1,1);
insert into Fan_band(fid,bid) values(2,2);
insert into Fan_band(fid,bid) values(3,3);
insert into Fan_band(fid,bid) values(4,4);
insert into Fan_band(fid,bid) values(5,1);

insert into fan_genre(fid,gen_id) values(1,3);
insert into fan_genre(fid,gen_id) values(2,3);
insert into fan_genre(fid,gen_id) values(3,5);
insert into fan_genre(fid,gen_id) values(4,6);
insert into fan_genre(fid,gen_id) values(5,4);

insert into venue(place,city,addressurl) value('brooklyn','New York city','www.google.com');
insert into venue(place,city,addressurl) value('manhattan','New York city','www.google.com');
insert into venue(place,city,addressurl) value('austin','Texas','www.google.com');
insert into venue(place,city,addressurl) value('journal square','Jersey city','www.google.com');

insert into concert(cid,vid,admin_id,description,vdate) values(1,1,1,'Metallica rocks madison','2001-01-01');
insert into concert(cid,vid,admin_id,description,vdate) values(2,2,2,'Iron Maiden in manhattan','2001-02-01');
insert into concert(cid,vid,admin_id,description,vdate) values(3,3,3,'The beatles??!','2002-01-01');
insert into concert(cid,vid,admin_id,description,vdate) values(4,4,1,'Eagles','2003-01-01');

insert into band_concert(bid,cid) values(1,1);
insert into band_concert(bid,cid) values(2,2);
insert into band_concert(bid,cid) values(3,3);
insert into band_concert(bid,cid) values(5,4);


insert into posts(user_id,description,pdate,like_count) values('sm1111','this is a test post for p1',now(),2);
insert into posts(user_id,description,pdate,like_count) values('ar2222','this is a test post for p2',now(),2);
insert into posts(user_id,description,pdate,like_count) values('fs3333','this is a test post for p3',now(),2);
insert into posts(user_id,description,pdate,like_count) values('gr4444','this is a test post for p4',now(),4);
insert into posts(user_id,description,pdate,like_count) values('sm5555','this is a test post for p5',now(),5);
insert into posts(user_id,description,pdate,like_count) values('sm1111','this is a test post for p6',now(),-5);

insert into concert_posts(pid,cid) values (1,1);
insert into concert_posts(pid,cid) values (2,2);
insert into concert_posts(pid,cid) values (3,3);

insert into band_posts(pid,bid) values (4,1);
insert into band_posts(pid,bid) values (5,2);
insert into band_posts(pid,bid) values (6,3);

insert into vote(user_id,cid,rating,vdate,review) values('sm5555',1,4,now(),'Mind blowing !');
insert into vote(user_id,cid,rating,vdate,review) values('sm1111',1,3,now(),'Decent crowd. Had fun.');
insert into vote(user_id,cid,rating,vdate,review) values('ar2222',3,4,now(),'Awesome Show !');
insert into vote(user_id,cid,rating,vdate,review) values('sm5555',4,3,now(),'It was okay.');

insert into participation(user_id,status,cid,part_time) values('sm1111','yes',1,now());
insert into participation(user_id,status,cid,part_time) values('ar2222','no', 1,now());
insert into participation(user_id,status,cid,part_time) values('sm5555','yes',1,now());
insert into participation(user_id,status,cid,part_time) values('gr4444','yes',2,now());
insert into participation(user_id,status,cid,part_time) values('sm1111','yes',3,now());
insert into participation(user_id,status,cid,part_time) values('ar2222','yes',4,now());
insert into participation(user_id,status,cid,part_time) values('ar2222','yes',2,now());

-- query for log in
--this was uname before but in our table user_id is the primary key so use that
 select user_id from user where user_id =? and password=?;

-- query to add following 
 insert into follows(follower,following,fdate) values(?,?,now());
--  query to delete following
 delete from follows where follower=? and following=?;

-- registration of new user
 insert into user(user_id,uname,password,dob,address,phnum,join_date) values(?,?,?,?,?,?,now());

--  insertion in fan (when like is clicked). 
insert into fan(user_id,fdate) values(?,now());
insert into fan_band(fid,bid) (select max(f.fid),bid from fan f, band b where bid=?);
-- deletion from fan( when like is clicked to unlike)
delete from fan_band where fid=? and bid=?;
delete from fan where fid=?;

-- inserting participation of user for a concert
insert into participation(user_id,status,cid,part_time) values(?,?,?,now());

-- inserting into vote
insert into vote(user_id,cid,rating,vdate) values(?,?,?,now());

-- Viewing profile of a user
Create view Profile as Select user_id,uname,dob,address,phnum from user where user_id=?;

-- query for recommendation of concerts based on user's history,friends history and user's genre
select cid from (
select p.cid,count(p.cid) max_concert from concert c,(
select f.follower user_id from follows f,user u where u.user_id=f.following and u.user_id='sm1111') temp,participation p
where p.status='yes' and p.user_id=temp.user_id and p.cid=c.cid and p.cid not in (select cid from participation p 
where p.user_id ='sm1111' and p.status='yes') group by p.cid) as temp having count(cid)>=max(max_concert);

select cid from participation where user_id='sm1111' and status='yes' and part_time BETWEEN DATE_SUB(now(), INTERVAL 3 MONTH) AND now();

select bc.cid from fan f,fan_genre fg,genre g,band_genre bg,band_concert bc where f.user_id='sm1111' and f.fid=fg.fid and 
fg.gen_id=g.gen_id and g.gen_id=bg.gen_id and bg.bid=bc.bid;

-- query for deleting an account
delete from user where user_id='fs3333';

-- query for creation of concert
insert into concert(vid,admin_id,description,vdate) values(4,(select fid from fan where user_id='sm1111'),'Its Beatles again','2013-01-01');
insert into band_concert(bid,cid) values((select bid from band where band_name='The Beatles'),(SELECT LAST_INSERT_ID()));

-- query for creation f band
insert into Band(band_name,bdate,admin_id) values('Metallica','1990-12-30','sm1111');

-- query for concert based posts
insert into posts(user_id,description,pdate) values('sm1111','this is a test post for p9',now());
insert into concert_posts(pid,cid) values ((SELECT LAST_INSERT_ID()),1);

-- query for band based posts

insert into posts(user_id,description,pdate) values('sm1111','this is a test post for p8',now());
insert into band_posts(pid,bid) values ((SELECT LAST_INSERT_ID()),1);

-- query to search
Select uname,user_id from user where uname like '%?%' union
Select bid,band_name from band where band_name like '%?%' union
Select description from concert where Description like '%?%' union
select subcat from genre where subcat like '%?%';


--query to update profile
update user set uname='sachin',password='password1',address='bangalore',phnum=12345,email_addr='s@b.com' where user_id='sm1111'
