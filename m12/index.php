<?php
$persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

function getPartsFromFullname($fullName){
    $splitting_name = explode(" ",$fullName);
    $splited_name = ['surname' => $splitting_name[0], 'name' => $splitting_name[1],'patronomyc' => $splitting_name[2],
    ] ;

    return $splited_name;

}

function getFullnameFromParts($surname, $name, $patronomyc){
    $fullName = [$surname, $name, $patronomyc];
    return implode(' ', $fullName);
}

function getShortName($fullName){
    $splited_name = getPartsFromFullname($fullName);
    $shortName = $splited_name["name"].' '.mb_substr($splited_name["surname"],0,1).".";

    return $shortName;
}

function getGenderFromName($fullName){
    $splited_name = getPartsFromFullname($fullName);
    $gender = 0;



    if (mb_substr($splited_name["surname"],-2,2) == "ва"){
        $gender = -1;
    } elseif (mb_substr($splited_name["surname"],-1,1) == "в"){
        $gender = 1;
    } else {
        $gender = 0;
    }

    $genderName = mb_substr($splited_name["name"],-1,1);

    if ($genderName == "a"){
        $gender = -1;
    } elseif ($genderName == "й" || $genderName == "н"){
        $gender = 1;
    } else {
        $gender = 0;
    }

    if (mb_substr($splited_name["patronomyc"],-3,3) == "вна"){
        $gender = -1;
    } elseif (mb_substr($splited_name["patronomyc"],-2,2) == "ич"){
        $gender = 1;
    } else {
        $gender = 0;
    }

    if (($gender <=> 0) === 1){
        return "male";
    } elseif (($gender <=> 0) === -1){
        return "female";
    } else {
        return "unknown";
    }

}

function getGenderDescription($array){

    $male = array_filter($array, function($array) {
        return (getGenderFromName($array['fullname']) == "male");
    });

    $female = array_filter($array, function($array) {
        return (getGenderFromName($array['fullname']) == "female");
    });

    $unknown = array_filter($array, function($array) {
        return (getGenderFromName($array['fullname']) == "unknown");
    });


    $summa = count($male) + count($female) + count($unknown);
    $resultMale =  round(count($male) / $summa * 100, 2);
    $resultFemale = round(count($female) / $summa * 100, 2);
    $resultUnknown = round(count($unknown) / $summa  * 100, 2);

    echo <<<HEREDOC
    Гендерный состав аудитории:<br>
    ---------------------------<br>
    Мужчины - $resultMale%<br>
    Женщины - $resultFemale%<br>
    Не удалось определить - $resultUnknown%<br>
    HEREDOC;

}

function getPerfectPartner($surname, $name, $patronomyc, $array){
    $surnamePerson = mb_convert_case($surname, MB_CASE_TITLE_SIMPLE);
    $namePerson = mb_convert_case($name, MB_CASE_TITLE_SIMPLE);
    $patronomycPerson = mb_convert_case($patronomyc, MB_CASE_TITLE_SIMPLE);
    $fullName = getFullnameFromParts($surnamePerson, $namePerson, $patronomycPerson);
    $mainGender = getGenderFromName($fullName);
    $randPerson = $array[rand(0,count($array)-1)]["fullname"];
    $randGender = getGenderFromName($randPerson);

    while ($mainGender == $randGender || $randGender === "Undefined"){
        $randPerson = $array[rand(0,count($array)-1)]["fullname"];
        $randGender = getGenderFromName($randPerson);
    }

    $mainName = getShortName($fullName);
    $randomName = getShortName($randPerson);
    $percent = rand(50,100)+rand(0,99)/100;

    echo <<<HEREDOC
    $mainName + $randomName =<br>
    ♡ Идеально на $percent% ♡
    HEREDOC;

}

echo
print_r(getFullnameFromParts("Иванов", "Иван", "Иванович") . "<br>");
echo "<br>";
print_r(getPartsFromFullname("Иванов Иван Иванович"));
echo "<br>";
print_r(getShortName("Иванов Иван Иванович") . "<br>");
echo "<br>";
print_r(getGenderFromName("Иванов Иван Иванович") . "<br>");
echo "<br>";
getGenderDescription($persons_array);
echo "<br>";
getPerfectPartner("ИВАнов", "ИВАН", "ИваНович", $persons_array);

?>

