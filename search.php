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
$keyword = $_POST['keyword'];

// 3. Construct MySQL query to search for records in the table
$sql = "SELECT * FROM project_dataset1_protein_protein
WHERE protein_1 LIKE '%$keyword%' OR protein_2 LIKE '%$keyword%'";

// 4. Execute query and fetch the results
$result = $conn->query($sql);

// 5. Format the results in HTML and return them to the frontend
if ($result->num_rows > 0) {
  echo "<h1>SEARCH RESULTS</h1>";
  // TABLE CONSTRUCTION
  echo "<table>
  <tr>
      <th>Entry ID</th>
      <th>PDB ID</th>
      <th>Mutation</th>
      <th>Protein 1</th>
      <th>Protein 2</th>
      <th>Experiment</th>
      <th>Temperature</th>
      <th>pH</th>
      <th>Binding Free Energy (kcal/mol)</th>
      <th>Change in Binding Free Energy (kcal/mol)</th>
      <th>Authors</th>
      <th>Journal</th>
      <th>PubMed ID</th>
  </tr>";

  while($row = $result->fetch_assoc()) {
    $pdb = $row["pdb"];
    $pdb_link = '<a href="https://www.rcsb.org/structure/'.$row["pdb"].'">'.$row["pdb"].'</a>';

    $pubmed = $row["pubmed_id"];
    $pubmed_link = '<a href="https://pubmed.ncbi.nlm.nih.gov/'.$row["pubmed_id"].'">'.$row["pubmed_id"].'</a>';

    $entry = $row["entry"];
    $entry_link = '<a href="id-search.php?id='.$row["entry"].'">'.$row["entry"].'</a>';

    // FETCHING DATA FROM EACH ROW OF EVERY COLUMN
    echo "<tr><td>".$entry_link."</td><td>".$pdb_link."</td><td>".$row["mutations2"]."</td>
    <td>".$row["protein_1"]."</td><td>".$row["protein_2"]."</td><td>".$row["experiment"]."</td>
    <td>".$row["temperature"]."</td><td>".$row["ph"]."</td><td>".$row["bfe"]."</td>
    <td>".$row["change_bfe"]."</td><td>".$row["authors"]."</td><td>".$row["journal"]."</td>
    <td>".$pubmed_link."</td></tr>";
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
    <title>Keyword Search</title>
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