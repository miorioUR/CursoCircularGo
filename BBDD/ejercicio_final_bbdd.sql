/*Creacion de tablas*/
CREATE TABLE IF NOT EXISTS shifts(

	shift_no int PRIMARY KEY AUTO_INCREMENT,
    emp_no int NOT NULL,
    start_time datetime NOT NULL DEFAULT NOW(),
    end_time datetime,
    FOREIGN KEY (emp_no) REFERENCES employees(emp_no)
);

/* 
 * El numero de dias es un campo calculable. 
 * Si es demasiado necesario porque se consulta con asiduidad se puede hacer una vista
 */
CREATE TABLE IF NOT EXISTS off_work(
	off_work_no int PRIMARY KEY AUTO_INCREMENT,
	emp_no int NOT NULL,
    start_time datetime NOT NULL,
    end_time datetime, 
    reason enum('medical', 'holiday', 'other'),
    FOREIGN KEY (emp_no) REFERENCES employees(emp_no)
);

--varchar source > blob si los documentos van en una carpeta y no se almacenan directamente en la BD
CREATE TABLE IF NOT EXISTS documents(
	document_no int PRIMARY KEY AUTO_INCREMENT,
	emp_no int NOT NULL,
    document blob NOT NULL,
    description varchar(255),
    FOREIGN KEY (emp_no) REFERENCES employees(emp_no)
);

/*
 * Tabla un poco rara ya que la tabla salaries es un historial de salarios persé.
 * Me ciño al enunciado del trigger 1
 */
CREATE TABLE IF NOT EXISTS salary_history(
	salary_history_no int PRIMARY KEY AUTO_INCREMENT,
	emp_no int NOT NULL,
    new_salary double(10,2) NOT NULL,
    update_date datetime DEFAULT NOW(),
    FOREIGN KEY (emp_no) REFERENCES employees(emp_no)
);

CREATE TABLE IF NOT EXISTS promotion_history(
	promotion_no int PRIMARY KEY AUTO_INCREMENT,
	emp_no int NOT NULL,
    new_qualification double(10,2) NOT NULL,
    update_date datetime DEFAULT NOW(),
    FOREIGN KEY (emp_no) REFERENCES employees(emp_no)
);

/*No considero que necesite ninguna constraint*/
ALTER TABLE employees ADD qualification varchar(255);

/*Trigger 1*/
CREATE TRIGGER salary_update
AFTER UPDATE ON salaries
FOR EACH ROW
BEGIN
	INSERT INTO salary_history (emp_no, new_salary) VALUES (NEW.emp_no, NEW.salary);
END

/*
 * Trigger 2
 * Un par de apreciaciones:
 * No he encontrado la forma de recorrer una tabla con un trigger por lo que lo ejecuto cuando un empleado empieza su turno
 * (Una posible mejora seria "personalizarlo" para leer el ultimo turno y si desde entonces ha sido su cumpleaños enviar un email)
 * 
 * No parece para nada una buena idea el envio de emails desde base de datos. Esto es algo que es siempre mejor hacerse en logica
 * Leyendo por internet he encontrado algunas soluciones mas o menos intrusivas, pero para poder testearlo lo guardare como archivo interno.
 * */
CREATE FUNCTION is_birthday(born_date DATETIME)
RETURNS BOOLEAN
NOT DETERMINISTIC
READS SQL DATA
BEGIN
	RETURN (DAY(NOW())=DAY(born_date) AND MONTH(NOW())=MONTH(born_date));
END

/* No soy capaz de fixear esto. He probado tambien con executes pero no lo consigo
 * Si conoces alguna forma o idea para corregir esto, estoy abierto a hacerlo
 * 
CREATE FUNCTION send_email(receiver varchar(255),message varchar(8192))
BEGIN
	DECLARE sql_string varchar(10000);
	SET sql_string = CONCAT('To:', receiver , '\r\n' , message, 'INTO OUTFILE \'/home/alumno/bbdd_employees/\'');
	SELECT sql_string;
END
*/

CREATE FUNCTION send_email(receiver varchar(255),message varchar(8192))
RETURNS BOOLEAN
DETERMINISTIC
BEGIN
	RETURN true;
END

CREATE TRIGGER birthday_email
AFTER UPDATE ON shifts
FOR EACH ROW 
BEGIN 
	DECLARE birthdate date;
	SELECT birth_date INTO birthdate FROM employees WHERE emp_no = NEW.emp_no;
	IF is_birthday(birthdate) THEN
    	CALL send_email('address@xd.com', 'Merry Christmas mr.handsome');
	END IF;
END



/*Procedure 1*/
CREATE PROCEDURE promote_employee(IN empno int,IN new_qualification varchar(255))
BEGIN
	UPDATE employees SET qualification = new_qualification WHERE emp_no = empno;
	INSERT INTO promotion_history (emp_no, new_qualification) VALUES (NEW.emp_no, NEW.new_qualification);
END

/*
 * SELECT * FROM employees e 
 * CALL promote_employee(10001, 'jefe de departamento') 
 */

/*La cantidad de empleados por dpto ya la hago en la consulta 3, aqui queria hacer algo mas general*/
CREATE PROCEDURE company_summary()
BEGIN
	DECLARE num_employees int;
	DECLARE num_deps int;
	DECLARE department_employees int;
	DECLARE dept_max_employees int;
 	DECLARE top_dept char(4);
	DECLARE top_dept_name varchar(255);
	DECLARE dept_avg_salary decimal(10,2);
	DECLARE avg_salary decimal(10,2);
	DECLARE max_salary decimal(10,2);
	DECLARE min_salary decimal(10,2);


	SELECT count(distinct e.emp_no), count(distinct dept_no) INTO num_employees,num_deps
	FROM employees e left join current_dept_emp cde on e.emp_no = cde.emp_no;

	SELECT count(distinct e.emp_no) INTO department_employees
	FROM employees e left join current_dept_emp cde on e.emp_no = cde.emp_no
	WHERE cde.dept_no is not NULL;
	
	/* Dividir esta consulta en las 2 de abajo ha sido una enorme mejora de rendimiento
	 * 
	SELECT d.dept_name, count(distinct e.emp_no), round(avg(s.salary),2) INTO top_dept_name,dept_max_employees,dept_avg_salary
	FROM employees e left join current_dept_emp cde on e.emp_no = cde.emp_no 
				 join departments d on cde.dept_no = d.dept_no 
				 join salaries s on e.emp_no = s.emp_no 
	GROUP BY d.dept_no 
	ORDER BY count(distinct e.emp_no) desc
	LIMIT 1;
	*/
	
	SELECT d.dept_no, d.dept_name INTO top_dept,top_dept_name
	FROM current_dept_emp cde join departments d on cde.dept_no = d.dept_no 
	GROUP BY d.dept_no 
	ORDER BY count(distinct cde.emp_no) desc
	LIMIT 1;

	SELECT count(distinct cde.emp_no), round(avg(s.salary),2) INTO dept_max_employees,dept_avg_salary
	FROM current_dept_emp cde join salaries s on cde.emp_no =s.emp_no
	WHERE cde.dept_no = top_dept;

	SELECT round(avg(salary),2), round(max(salary),2), round(min(salary),2) INTO avg_salary,max_salary,min_salary
	FROM salaries;

	SELECT num_employees as "total_employees", avg_salary, max_salary, min_salary, num_deps as "total_departments",  100*department_employees/num_employees as "%employees_in_dept",
		   top_dept_name as "biggest_dept", dept_max_employees as "employees_in_biggest_dept", dept_avg_salary as "avg_salary_in_biggest_dept";
END

/*Momento test*/
SET profiling=1;
CALL company_summary();
SHOW profiles;

CREATE INDEX idx_salary ON salaries(salary);
CREATE INDEX idx_dept ON departments(dept_no);
CREATE INDEX idx_employee ON employees(emp_no);



/*
 * Cuando queramos hacer sumas de salarios es absurdo contar el historico de salarios,
 * aunque este tenga sentido para algunas medias (como las del informe del procedimiento)
 * Esta vista sera un recurso al que accedamos muy frecuentemente para control de gasto
 * Tambien nos servira si queremos mejorar la eficiencia de company_summary, 
 * aunque la media de salarios sera respecto a la actualidad.
*/
CREATE VIEW last_salaries AS SELECT * FROM salaries GROUP BY emp_no HAVING max(to_date);
/*
 * Seria buena idea considerar crearlo como tabla si queremos ponerle un indice (cosa de mucha utilidad para la siguiente consulta)
 * No lo haré porque creo que es tomarme demasiada libertad y rompe con la idea de negocio
 * de esta BD, donde current_dept emp y dept_emp_latest_date son vistas
 */

/*Consulta 1 */
SELECT d.dept_name, round(sum(ls.salary),2) as "spent_on_salaries",
		(SELECT concat(e2.first_name , " ", e2.last_name) 
		 FROM employees e2 join last_salaries ls2 on e2.emp_no = ls2.emp_no
		 WHERE ls2.salary >= max(ls.salary)
		 LIMIT 1) as "highest_paid_employee", max(ls.salary) as "highest_salary"
FROM departments d join current_dept_emp cde on d.dept_no = cde.dept_no 
				   join employees e on cde.emp_no = e.emp_no
				   join last_salaries ls on e.emp_no = ls.emp_no 
GROUP BY d.dept_no
ORDER BY spent_on_salaries DESC

/*Consulta 2 */
SELECT d.dept_name, max(ls.salary) - min(ls.salary) as "salary_gap"
FROM departments d join current_dept_emp cde on d.dept_no = cde.dept_no 
				   join employees e on cde.emp_no = e.emp_no
				   join last_salaries ls on e.emp_no = ls.emp_no 
GROUP BY d.dept_no
ORDER BY salary_gap DESC 

/*Consulta 3
 * Hay fechas de contratos que acaban en el año 9999, para tener una muestra mas realista
 * he añadido la condicion de que el contrato acabe antes del año 2100 y he limpiado estos datos*/
SELECT d.dept_name , count(distinct de.emp_no) as "employees_count", 
	(SELECT avg(DATEDIFF(de2.to_date,de2.from_date))
	 FROM dept_emp de2
	 WHERE de2.dept_no = d.dept_no and YEAR(de2.to_date)<2100) as "average_days"
FROM departments d join dept_emp de on d.dept_no = de.dept_no
GROUP BY d.dept_no 
ORDER BY employees_count DESC ;

/* La original sin limpieza de datos seria la siguiente (por si acaso)*/
SELECT d.dept_name , count(distinct de.emp_no) as "employees_count", avg(DATEDIFF(de.to_date,de.from_date)) as "average_days"
FROM departments d join dept_emp de on d.dept_no = de.dept_no
GROUP BY d.dept_no
ORDER BY employees_count DESC ;

CREATE INDEX idx_emp_in_dept ON dept_emp(emp_no);
CREATE INDEX idx_dept_for_emp ON dept_emp(dept_no);
/*La tercera ya es rapida perse pero creo que esto puede servir*/
CREATE INDEX idx_dept_emp_from ON dept_emp(from_date);
CREATE INDEX idx_dept_emp_to ON dept_emp(to_date);
/*Es complicado usar mas indices en estas consultas
 * Algunos ya los hemos creado en el summary, incluso aquellos que son claves primarias
 * (desconozco si el SGBD los crea)
 * Ademas en las consultas se usan vistas que son enormemente mas pequeñas en lugar
 * de la tabla salaries o la tabla dept_emp.
 * Lo mejor que se me ocurre seria un procedimiento en la consulta 1 para juntar las tablas 2 a 2
 * Puede que tambien sustituir las vistas por tablas con indices y condiciones como las que crearon las vistas¿?
 *
 * */

