import re


def drop_tables_if_exist(tables, file):
    for table in tables:
        file.write(f'DROP TABLE IF EXISTS {table};\n')


def create_table(name, fields, file):
    if len(fields) == 0:
        return
    file.write(f'CREATE TABLE {name}(\n')
    temp = ''
    for value in fields:
        temp += f'\t{value},\n'
    temp = temp[0:-2] + '\n);\n'
    file.write(temp)


def parse_movies():
    values = []
    name = 'movies.csv'
    file = open(name, 'r')
    file.readline()
    for line in file.readlines():
        temp = line.replace('\n', '')
        id = re.findall('^\d+', temp)[0]
        year = 'null'
        try:
            year = re.findall('\(\d+\)', temp)[0][1:-1]
        except:
            pass
        title = str(re.findall('(?<=,)[^\n]+(?=\(\d{4}\))', temp)[0]).strip('"').strip(' ') if year != 'null' else str(
            re.findall('(?<=,)[^\n]+(?=,)', temp)[0]).strip('"').strip(' ')

        title = title.replace("'", "''")

        genres = re.findall('(,)([^,]*$)', temp)[0][-1].replace("'", "''")
        values.append(f"{id},'{title}', {year}, '{genres}'")
    return values


def parse_ratings():
    name = 'ratings.csv'
    file = open(name, 'r')
    file.readline()
    values = list(map(lambda x: x.strip('\n').replace("'", "''"), file.readlines()))
    return values


def parse_tags():
    name = 'tags.csv'
    file = open(name, 'r')
    file.readline()
    values = list(map(lambda x: x.replace('|', ', ').strip('\n').replace("'", "''"), file.readlines()))
    nv = []
    for line in values:
        temp = line.split(',')
        nv.append(
            f"{temp[0]}, {temp[1]}, '{temp[2].strip(' ')}',{temp[3]}")

    return nv


def parse_users():
    name = 'users.txt'
    file = open(name, 'r')
    values = list(map(lambda x: x.replace('|', ', ').strip('\n').replace("'", "''"), file.readlines()))
    nv = []
    for line in values:
        temp = line.split(',')
        nv.append(
            f"{temp[0]}, '{temp[1].strip(' ')}', '{temp[2].strip(' ')}','{temp[3].strip(' ')}', '{temp[4].strip(' ')}', '{temp[5].strip(' ')}'")

    return nv


def write_movies(values, file):
    file.write('\nINSERT INTO movies(id, title,year,genres) VALUES \n')
    for val in values:
        if values[-1] == val:
            file.write('\t(' + val + ')\n')
            continue
        file.write('\t(' + val + '),\n')
    file.write(";\n")


def write_ratings(values, file):
    file.write('\nINSERT INTO ratings (user_id, movie_id, rating, timestamp) VALUES \n')
    for val in values:
        if values[-1] == val:
            file.write('\t(' + val + ')\n')
            continue
        file.write('\t(' + val + '),\n')
    file.write(";\n")


def write_users(values, file):
    file.write('\nINSERT INTO users VALUES \n')
    for val in values:
        if values[-1] == val:
            file.write('\t(' + val + ')\n')
            continue
        file.write('\t(' + val + '),\n')
    file.write(";\n")


def write_tags(values, file):
    file.write('\nINSERT INTO tags VALUES \n')
    for val in values:
        if values[-1] == val:
            file.write('\t(' + val + ')\n')
            continue
        file.write('\t(' + val + '),\n')
    file.write(";\n")


if __name__ == '__main__':
    file_name = 'db_init.sql'
    tables = ['movies', 'ratings', 'tags', 'users']
    file = open(file_name, 'w')
    drop_tables_if_exist(tables, file)
    movies_fields = (
        'id integer primary key',
        'title text',
        'year integer',
        'genres text')

    ratings_fields = (
        'id integer primary key',
        'user_id integer',
        'movie_id integer',
        'rating real',
        'timestamp integer')
    tags_fields = (
        'user_id integer',
        'movie_id integer',
        'tag text',
        'timestamp integer')

    users_fields = (
        'id integer primary key',
        'name text',
        'email text',
        'gender text',
        'register_date text',
        'occupation text')

    create_table('movies', movies_fields, file)
    create_table('ratings', ratings_fields, file)
    create_table('tags', tags_fields, file)
    create_table('users', users_fields, file)

    write_movies(parse_movies(), file)
    write_users(parse_users(), file)
    write_ratings(parse_ratings(), file)
    write_tags(parse_tags(), file)