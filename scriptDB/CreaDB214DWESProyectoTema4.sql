create database if not exists DB214DWESProyectoTema4;



/*SET GLOBAL validate_password.length = 2;
SET GLOBAL validate_password.number_count = 0;
SET GLOBAL validate_password.policy=LOW;*/

create user 'User214DWESProyectoTema4'@'%' identified by 'paso';
grant all privileges on DB214DWESProyectoTema4.* to 'User214DWESProyectoTema4'@'%';
flush privileges;

use DB214DWESProyectoTema4;

create table if not exists Departamento(
    CodDepartamento varchar(3) primary key not null,
    DescDepartamento varchar(255) not null,
    FechaBaja date,
    VolumenNegocio float not null
);