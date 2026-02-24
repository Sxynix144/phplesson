<?php 

$fruits = array("apple","banana","mango");

echo $fruits[0] . "<br>";
echo $fruits[1] . "<br><br>";

$student = array(
    "name" => "Micheal",
    "age" => 21,
    "course" => "IT"
);

echo "Name: " . $student["name"] . "<br>";
echo "Age: " . $student["age"] . "<br>";
echo "Course: " . $student["course"] . "<br><br>";

$students = array (
    array("name" => "Micheal", "age" => 21, "course" => "IT"),
    array("name" => "Anna", "age" => 19, "course" => "CS"),
    array("name" => "Allen", "age" => 101, "course" => "MATH")
);      

echo $students[0]["name"] . "<br>";
echo $students[1]["course"] . "<br>";
echo $students[2]["age"] . "<br><br>";

$start = "apple,banana,orange";

$fruits = explode(",", $start);

print_r($fruits);
echo "<br><br>";

$fruits2 = array("apple","banana","mango");

$text = implode(" - ", $fruits2);

echo $text;

?>