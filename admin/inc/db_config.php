<?php

// database connection
$host_name = 'localhost';
$user_name = 'root';
$password = '';
$database = 'hbwebsite';

$conn = mysqli_connect($host_name, $user_name, $password, $database);

if (!$conn) {
    die("cannot connect to database" . mysqli_connect_error());
}


// function for filteration 
function filteration($data)
{
    foreach ($data as $key => $value) {
        $value = trim($value);
        $value = stripslashes($value);
        $value = strip_tags($value);
        $value = htmlspecialchars($value);
        $data[$key] = $value;
    }
    return $data;
}

// for fetching complete table data
function selectAll($table)
{
    $conn = $GLOBALS['conn'];
    $result = mysqli_query($conn, "SELECT * FROM $table");
    return $result;
}


// function for fetching data with prepared query 
function select($sql_query, $data_types, $values)
{
    $conn = $GLOBALS['conn'];
    if ($stmt = mysqli_prepare($conn, $sql_query)) {
        mysqli_stmt_bind_param($stmt, $data_types, ...$values);
        if (mysqli_stmt_execute($stmt)) {
            $result =  mysqli_stmt_get_result($stmt);
            mysqli_stmt_close($stmt);
            return $result;
        } else {
            mysqli_stmt_close($stmt);
            die("Query cannot be executed - Select");
        }
    } else {
        die("Query cannot be prepared - Select");
    }
}

//function for updating data with prepared query 
function update($sql_query, $data_types, $values)
{
    $conn = $GLOBALS['conn'];
    if ($stmt = mysqli_prepare($conn, $sql_query)) {
        mysqli_stmt_bind_param($stmt, $data_types, ...$values);
        if (mysqli_stmt_execute($stmt)) {
            $result =  mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $result;
        } else {
            mysqli_stmt_close($stmt);
            die("Query cannot be executed - update");
        }
    } else {
        die("Query cannot be prepared - update");
    }
}

//function for inserting data with prepared query 
function insert($sql_query, $data_types, $values)
{
    $conn = $GLOBALS['conn'];
    if ($stmt = mysqli_prepare($conn, $sql_query)) {
        mysqli_stmt_bind_param($stmt, $data_types, ...$values);
        if (mysqli_stmt_execute($stmt)) {
            $result =  mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $result;
        } else {
            mysqli_stmt_close($stmt);
            die("Query cannot be executed - insert");
        }
    } else {
        die("Query cannot be prepared - insert");
    }
}

//function for deleting data with prepared query 
function delete($sql_query, $data_types, $values)
{
    $conn = $GLOBALS['conn'];
    if ($stmt = mysqli_prepare($conn, $sql_query)) {
        mysqli_stmt_bind_param($stmt, $data_types, ...$values);
        if (mysqli_stmt_execute($stmt)) {
            $result =  mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $result;
        } else {
            mysqli_stmt_close($stmt);
            die("Query cannot be executed - delete");
        }
    } else {
        die("Query cannot be prepared - delete");
    }
}
