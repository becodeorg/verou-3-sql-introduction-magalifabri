## The Mission

### 🌱 Must-have features

1. Get familiar with [the basics](./SQL-basics.md) and set up a database
2. Make the following tables and populate them with some dummy data (have at least two entries for every table)
    

- groups: id, name, location, start_date, max_participants
```sql
CREATE TABLE groups (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    location VARCHAR(255),
    start_date DATE
);

INSERT INTO `groups` (
    `name`,
    `location`,
    `start_date`
) VALUES (
    'elon-1',
    'Ghent',
    '2022-06-01'
), (
    'zuck-1',
    'Ghent',
    '2022-09-01'
);
```

- learners: id, name, email, active
```sql
CREATE TABLE learners (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255),
    active BOOLEAN
);

INSERT INTO `learners` (
    `name`,
    `email`,
    `active`
) VALUES (
    'Pikachu',
    'chuchu@hotmail.com',
    1
), (
    'Squirtle',
    'squirt@hotmail.com',
    1
), (
    'Bulbasaur',
    'bulb@hotmail.com',
    1
)
```

- coaches: id, name
```sql
CREATE TABLE coaches (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255)
);

INSERT INTO `coaches` (`name`)
VALUES ('Elon'), ('Mark');
```

3. Try the following selects

- Get all data from the groups<sup>\*</sup>
```sql
SELECT * FROM `groups`;
```

- Get the name and email of the first learner, and alias the name to learner_name<sup>\*</sup>
```sql
SELECT `name` AS 'learner_name', `email` FROM `learners` LIMIT 1;
```

4. 💩 happens - a group needs to be postponed
- Update the start date of the first_group (make it two months later)<sup>\*</sup>
```sql
UPDATE `groups` SET `start_date` = '2022-08-01' WHERE `id` = 1;
```

- Introduce a new field `status` which can contain a long text indicating the reason for postponing (bonus points if it's a creative one)
```sql
ALTER TABLE `groups`
ADD `status` TEXT;

UPDATE `groups` SET `status` = 'Coach preoccupied launching rockets into space' WHERE `id` = 1;
```

5. One of the learners changed his/her mind and decided to be an astronaut
    - Delete someone from the learners table<sup>\*</sup>
```sql
```

### 🌼 Nice to have (doable)

6. A learner belongs to a group, and a group has a coach
    - Find a technique to make this connection in the database (what of the field is unique to a record, so we can refer to it?)
7. We want all the data
    - Select a coach and all related groups<sup>\*</sup>
    - Select all the above, but also all learners from this group who are still active<sup>\*</sup>

### 🌳 Nice to have (hard)

Bonus round: try some steps again, but this time run your SQL from PHP.
You'll need to connect PHP to the database first. What techniques can you find to do so? Why do you choose one or another? Don't overthink the structure at this point, one file is enough.

Enjoy the ride! Don't forget to look back at what you've achieved so far every once in a while - you'll be old before you know 🙃
![](https://media.giphy.com/media/2nJgpMuR2fVn2/giphy.gif)