<?php
// 1. Establish connection to MySQL database
$db_host = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "project";

// Create connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

// 2. Retrieve search term entered by the user from frontend
// Search
if (!empty($_POST['protein1'])) {
  $protein_1 = $_POST['protein1'];
}
if (!empty($_POST['source_org'])) {
  $source_org = $_POST['source_org'];
}
if (!empty($_POST['pdb_id'])) {
  $pdb_id = $_POST['pdb_id'];
}
if (!empty($_POST['wild_type'])) {
  $wild_type = $_POST['wild_type'];
}
if (!empty($_POST['mutation_type'])) {
  $mutation_type = $_POST['mutation_type'];
}
if (!empty($_POST['original'])) {
  $original = $_POST['original'];
}
if (!empty($_POST['mutated'])) {
  $mutated = $_POST['mutated'];
}
if (!empty($_POST['experiment'])) {
  $experiment = $_POST['experiment'];
}
if (!empty($_POST['min_temp'])) {
  $min_temp = $_POST['min_temp'];
}
if (!empty($_POST['max_temp'])) {
  $max_temp = $_POST['max_temp'];
}
if (!empty($_POST['min_ph'])) {
  $min_ph = $_POST['min_ph'];
}
if (!empty($_POST['max_ph'])) {
  $max_ph = $_POST['max_ph'];
}
if (!empty($_POST['min_bfe'])) {
  $min_bfe = $_POST['min_bfe'];
}
if (!empty($_POST['max_bfe'])) {
  $max_bfe = $_POST['max_bfe'];
}
if (!empty($_POST['min_c_bfe'])) {
  $min_c_bfe = $_POST['min_c_bfe'];
}
if (!empty($_POST['max_c_bfe'])) {
  $max_c_bfe = $_POST['max_c_bfe'];
}
if (!empty($_POST['pubmed_id'])) {
  $pubmed_id = $_POST['pubmed_id'];
}
if (!empty($_POST['author'])) {
  $author = $_POST['author'];
}
if (!empty($_POST['journal'])) {
  $journal = $_POST['journal'];
}
if (!empty($_POST['sort_type'])) {
  $sort_type = $_POST['sort_type'];
}
if (!empty($_POST['sort_field'])) {
  $sort_field = $_POST['sort_field'];
}

// Display columns
if (isset($_POST['entry_f'])) {
  $entry_f = $_POST['entry_f'];
}
if (isset($_POST['pdb_f'])) {
  $pdb_f = $_POST['pdb_f'];
}
if (isset($_POST['protein1_f'])) {
  $protein1_f = $_POST['protein1_f'];
}
if (isset($_POST['protein2_f'])) {
  $protein2_f = $_POST['protein2_f'];
}
if (isset($_POST['mutation_f'])) {
  $mutation_f = $_POST['mutation_f'];
}
if (isset($_POST['experiment_f'])) {
  $experiment_f = $_POST['experiment_f'];
}
if (isset($_POST['temperature_f'])) {
  $temperature_f = $_POST['temperature_f'];
}
if (isset($_POST['ph_f'])) {
  $ph_f = $_POST['ph_f'];
}
if (isset($_POST['bfe_f'])) {
  $bfe_f = $_POST['bfe_f'];
}
if (isset($_POST['c_bfe_f'])) {
  $c_bfe_f = $_POST['c_bfe_f'];
}
if (isset($_POST['pubmed_f'])) {
  $pubmed_f = $_POST['pubmed_f'];
}
if (isset($_POST['authors_f'])) {
  $authors_f = $_POST['authors_f'];
}
if (isset($_POST['journal_f'])) {
  $journal_f = $_POST['journal_f'];
}

// 3. Construct MySQL query to search for records in the table
//$sql = "SELECT entry, protein_1, protein_2 FROM project_dataset1_protein_protein";

$display_col_arr = array("entry_f", "pdb_f", "protein1_f", "protein2_f", "mutation_f",
"experiment_f", "temperature_f", "ph_f", "bfe_f", "c_bfe_f", "pubmed_f", "authors_f",
"journal_f");

$display_col_dict = array(
  "entry_f" => "entry",
  "pdb_f" => "pdb",
  "protein1_f" => "protein_1",
  "protein2_f" => "protein_2",
  "mutation_f" => "mutations2",
  "experiment_f" => "experiment",
  "temperature_f" => "temperature",
  "ph_f" => "ph",
  "bfe_f" => "bfe",
  "c_bfe_f" => "change_bfe",
  "pubmed_f" => "pubmed_id",
  "authors_f" => "authors",
  "journal_f" => "journal"
);

$selected_fields = "";

foreach ($display_col_arr as $value) {
  if (isset($_POST[$value])) {
    if ($value == "entry_f") {
      $selected_fields.= "project_dataset1_protein_protein.".$display_col_dict[$value].", ";
    } else {
      $selected_fields.= $display_col_dict[$value].", ";
    }
  }
}
$selected_fields = rtrim($selected_fields, ", "); // remove last comma

// echo "$selected_fields";
// $sql = "SELECT ".$selected_fields." FROM project_dataset1_protein_protein WHERE protein_1 LIKE '%$protein_1%'";
$sql = "SELECT ".$selected_fields." FROM project_dataset1_protein_protein WHERE";

if (!empty($mutation_type)) {
  $sql = rtrim($sql, " WHERE"); // remove "WHERE"
    $comma = "JOIN (SELECT entry, 
    LENGTH(mutations2)- LENGTH(REGEXP_REPLACE(mutations2, ',','')) AS comma_count 
    FROM project_dataset1_protein_protein) comma_table on 
    project_dataset1_protein_protein.entry = comma_table.entry";
  if ($mutation_type == "multiple") {
    $sql.= " $comma WHERE comma_count > 1 AND";
    //$sql.= " mutations2 LIKE '%,%,%' AND";
  } elseif ($mutation_type == "double") {
    $sql.= " $comma WHERE comma_count = 1 AND";
  } else {
    $sql.= " $comma WHERE comma_count = 0 AND";
  }
}
if (!empty($protein_1)) {
  $sql.= " protein_1 LIKE '%$protein_1%' AND";
}
if (!empty($source_org)) {
  $sql.= " (protein_1 LIKE '%$source_org%' OR protein_2 LIKE '%$source_org%') AND";
}
if (!empty($pdb_id)) {
  $sql.= " pdb = '$pdb_id' AND";
}
if (!empty($wild_type)) {
  $sql.= " mutations2 = '$wild_type' AND";
}
if (!empty($original) && !empty($mutated)) {
  $single_char = $original.'_'.$mutated; // 1 char
  $double_char = $original.'__'.$mutated; // 2 char
  $triple_char = $original.'___'.$mutated; // 3 char
  $quad_char = $original.'____'.$mutated; // 4 char
  $midpos_double_char = ','.$double_char.','; // mid postion, 2 char
  $midpos_triple_char = ','.$triple_char.','; // mid position, 3 char
  $midpos_quad_char = ','.$quad_char.','; // mid position, 4 char
  $lastpos_double_char = ','.$double_char; // last postion, 2 char
  $lastpos_triple_char = ','.$triple_char; // last position, 3 char
  $lastpos_quad_char = ','.$quad_char; // last position, 4 char
  $sql.= " (mutations2 LIKE '$single_char%' 
  OR mutations2 LIKE '$double_char%' 
  OR mutations2 LIKE '$triple_char%' 
  OR mutations2 LIKE '$quad_char%' 
  OR mutations2 LIKE '%$midpos_double_char%' 
  OR mutations2 LIKE '%$midpos_triple_char%' 
  OR mutations2 LIKE '%$midpos_quad_char%' 
  OR mutations2 LIKE '%$lastpos_double_char' 
  OR mutations2 LIKE '%$lastpos_triple_char' 
  OR mutations2 LIKE '%$lastpos_quad_char') AND";
} elseif (!empty($original)) {
  $sql.= " (mutations2 LIKE '$original%' OR mutations2 LIKE '%,$original%') AND";
} elseif (!empty($mutated)) {
  $sql.= " (mutations2 LIKE '%$mutated' OR mutations2 LIKE '%$mutated,%') AND";
}
if (!empty($experiment)) {
  $sql.= " experiment = '$experiment' AND";
}

if (isset($min_temp)) {
  $sql.= " temperature >= $min_temp AND";
}
if (isset($max_temp)) {
  $sql.= " temperature <= $max_temp AND";
}
if (isset($min_ph)) {
  $sql.= " ph >= $min_ph AND";
}
if (isset($max_ph)) {
  $sql.= " ph <= $max_ph AND";
}
if (isset($min_bfe)) {
  $sql.= " bfe >= $min_bfe AND";
}
if (isset($max_bfe)) {
  $sql.= " bfe <= $max_bfe AND";
}
if (isset($min_c_bfe)) {
  $sql.= " change_bfe >= $min_c_bfe AND";
}
if (isset($max_c_bfe)) {
  $sql.= " change_bfe <= $max_c_bfe AND";
}
if (!empty($pubmed_id)) {
  $sql.= " pubmed_id = '$pubmed_id' AND";
}
if (!empty($author)) {
  $sql.= " authors like '%$author%' AND";
}
if (!empty($journal)) {
  $sql.= " journal like '%$journal%' AND";
}

$sql = rtrim($sql, " AND"); // remove last "AND"

if (!empty($sort_type) && !empty($sort_field)) {
  $sql.= " ORDER BY $sort_field $sort_type";
  
}
// echo "$sql";
// 4. Execute query and fetch the results
$result = $conn->query($sql);

// 5. Format the results in HTML and return them to the frontend
$html_col_dict = array(
  "entry_f" => "<th>Entry ID</th>",
  "pdb_f" => "<th>PDB ID</th>",
  "protein1_f" => "<th>Protein 1</th>",
  "protein2_f" => "<th>Protein 2</th>",
  "mutation_f" => "<th>Mutation</th>",
  "experiment_f" => "<th>Experiment</th>",
  "temperature_f" => "<th>Temperature</th>",
  "ph_f" => "<th>pH</th>",
  "bfe_f" => "<th>Binding Free Energy (kcal/mol)</th>",
  "c_bfe_f" => "<th>Change in Binding Free Energy (kcal/mol)</th>",
  "pubmed_f" => "<th>PubMed ID</th>",
  "authors_f" => "<th>Authors</th>",
  "journal_f" => "<th>Journal</th>"
);

$html_cols = "";

foreach ($display_col_arr as $value) {
  if (isset($_POST[$value])) {
    $html_cols.= $html_col_dict[$value];
  }
}

// echo "$html_cols";
if ($result->num_rows > 0) {
  echo "<h1>SEARCH RESULTS</h1>";
    // TABLE CONSTRUCTION
  echo "<table>
  <tr>".$html_cols.
  "</tr>";

  while($row = $result->fetch_assoc()) {

    $output = "";
    foreach ($display_col_arr as $value) {
      if (isset($_POST[$value])) {
        if ($value == "pdb_f") {
          $pdb = $row["pdb"];
          $pdb_link = '<a href="https://www.rcsb.org/structure/'.$row["pdb"].'">'.$row["pdb"].'</a>';
          $output.= "<td>".$pdb_link."</td>";
        } elseif ($value == "pubmed_f") {
          $pubmed = $row["pubmed_id"];
          $pubmed_link = '<a href="https://pubmed.ncbi.nlm.nih.gov/'.$row["pubmed_id"].'">'.$row["pubmed_id"].'</a>';
          $output.= "<td>".$pubmed_link."</td>";
        } elseif ($value == "entry_f") {
          $entry = $row["entry"];
          $entry_link = '<a href="id-search.php?id='.$row["entry"].'">'.$row["entry"].'</a>';
          $output.= "<td>".$entry_link."</td>";
        } else {
          $output.= "<td>".$row[$display_col_dict[$value]]."</td>";
        }
      }
    }

    echo "<tr>".$output."</tr>";
  }
  echo "</table>";
} else {
  echo "No results found.";
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
 
<head>
    <meta charset="UTF-8">
    <title>Advanced Search</title>
    <!-- CSS FOR STYLING THE PAGE -->
    <style>
        table {
            margin: 0 auto;
            border: 1px solid black;
            border-collapse: collapse;
        }
 
        h1 {
            text-align: center;
            font-size: x-large;
            font-family: 'Arial', 'Gill Sans', 'Gill Sans MT',
            'Calibri', 'Trebuchet MS', 'sans-serif';
        }

        th {
            background-color: #20B2AA;
            color: white;
            font-size: large;
            font-weight: bold;
            font-family: 'Monaco', 'Arial', 'Gill Sans', 'Gill Sans MT',
            'Calibri', 'Trebuchet MS', 'sans-serif';
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }
 
        td {
            background-color: #F8F8FF;
            font-family: 'Monaco', 'Arial', 'Gill Sans', 'Gill Sans MT',
            'Calibri', 'Trebuchet MS', 'sans-serif';
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }
    </style>
</head>

</html>