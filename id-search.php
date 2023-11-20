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
if (isset($_POST['id'])) {
    $id = $_POST['id'];
}
if (empty($id)) {
    $id = $_GET["id"];
  }

// 3. Construct MySQL query to search for records in the table
$sql = "SELECT * FROM project_dataset1_protein_protein WHERE entry = '$id'";

// 4. Execute query and fetch the results
$result = $conn->query($sql);

// 5. Format the results in HTML and return them to the frontend
if ($result->num_rows > 0) {
  echo "<span style='font-family:Arial; font-size: 25px; font-weight: bold'>"."<div style='text-align: center;'>SEARCH RESULTS</div><br /><br />"."</span>";
  // output data of each row
  while($row = $result->fetch_assoc()) {
    $pdb = $row["pdb"];
    $pdb_link = '<a href="https://www.rcsb.org/structure/'.$row["pdb"].'">'.$row["pdb"].'</a>';

    $pubmed = $row["pubmed_id"];
    $pubmed_link = '<a href="https://pubmed.ncbi.nlm.nih.gov/'.$row["pubmed_id"].'">'.$row["pubmed_id"].'</a>';

    echo "<table border=1 cellspacing=1 cellpadding=1 style='margin: 0 auto;border: 1px solid black;border-collapse:collapse'>
        <tr> 
            <td bgcolor=#20B2AA style='font-weight: bold;font-family:Arial;padding: 5px;text-align: center'><font color=white>Entry ID</font></td>
            <td bgcolor=#F8F8FF style='font-family:Arial;padding: 5px;text-align: center'>$row[entry]</td>
        </tr>
        <tr> 
            <td style='font-weight: bold;font-family:Arial;padding: 5px;text-align: center'>Protein Information</td>
        </tr>
        <tr> 
            <td bgcolor=#20B2AA style='font-weight: bold;font-family:Arial;padding: 5px;text-align: center'><font color=white>Protein 1</font></td>
            <td bgcolor=#F8F8FF style='font-family:Arial;padding: 5px;text-align: center'>$row[protein_1]</td>
        </tr>
        <tr> 
            <td bgcolor=#20B2AA style='font-weight: bold;font-family:Arial;padding: 5px;text-align: center'><font color=white>Protein 2</font></td>
            <td bgcolor=#F8F8FF style='font-family:Arial;padding: 5px;text-align: center'>$row[protein_2]</td>
        </tr>
        <tr> 
            <td bgcolor=#20B2AA style='font-weight: bold;font-family:Arial;padding: 5px;text-align: center'><font color=white>PDB ID</font></td>
            <td bgcolor=#F8F8FF style='font-family:Arial;padding: 5px;text-align: center'>$pdb_link</td>
        </tr>
        <tr> 
            <td bgcolor=#20B2AA style='font-weight: bold;font-family:Arial;padding: 5px;text-align: center'><font color=white>Mutation</font></td>
            <td bgcolor=#F8F8FF style='font-family:Arial;padding: 5px;text-align: center'>$row[mutations2]</td>
        </tr>
        <tr> 
            <td style='font-weight: bold;font-family:Arial;padding: 5px;text-align: center'>Thermodynamic Data</td>
        </tr>
        <tr> 
            <td bgcolor=#20B2AA style='font-weight: bold;font-family:Arial;padding: 5px;text-align: center'><font color=white>Binding Free Energy (kcal/mol)</font></td>
            <td bgcolor=#F8F8FF style='font-family:Arial;padding: 5px;text-align: center'>$row[bfe]</td>
        </tr>
        <tr> 
            <td bgcolor=#20B2AA style='font-weight: bold;font-family:Arial;padding: 5px;text-align: center'><font color=white>Change in Binding Free Energy (kcal/mol)</font></td>
            <td bgcolor=#F8F8FF style='font-family:Arial;padding: 5px;text-align: center'>$row[change_bfe]</td>
        </tr>
        <tr> 
            <td style='font-weight: bold;font-family:Arial;padding: 5px;text-align: center'>Experimental Conditions</td>
        </tr>
        <tr> 
            <td bgcolor=#20B2AA style='font-weight: bold;font-family:Arial;padding: 5px;text-align: center'><font color=white>Experiment</font></td>
            <td bgcolor=#F8F8FF style='font-family:Arial;padding: 5px;text-align: center'>$row[experiment]</td>
        </tr>
        <tr> 
            <td bgcolor=#20B2AA style='font-weight: bold;font-family:Arial;padding: 5px;text-align: center'><font color=white>Temperature</font></td>
            <td bgcolor=#F8F8FF style='font-family:Arial;padding: 5px;text-align: center'>$row[temperature]</td>
        </tr>
        <tr> 
            <td bgcolor=#20B2AA style='font-weight: bold;font-family:Arial;padding: 5px;text-align: center'><font color=white>pH</font></td>
            <td bgcolor=#F8F8FF style='font-family:Arial;padding: 5px;text-align: center'>$row[ph]</td>
        </tr>
        <tr> 
            <td style='font-weight: bold;font-family:Arial;padding: 5px;text-align: center'>Reference</td>
        </tr>
        <tr> 
            <td bgcolor=#20B2AA style='font-weight: bold;font-family:Arial;padding: 5px;text-align: center'><font color=white>PubMed ID</font></td>
            <td bgcolor=#F8F8FF style='font-family:Arial;padding: 5px;text-align: center'>$pubmed_link</td>
        </tr>
        <tr> 
            <td bgcolor=#20B2AA style='font-weight: bold;font-family:Arial;padding: 5px;text-align: center'><font color=white>Authors</font></td>
            <td bgcolor=#F8F8FF style='font-family:Arial;padding: 5px;text-align: center'>$row[authors]</td>
        </tr>
        <tr> 
            <td bgcolor=#20B2AA style='font-weight: bold;font-family:Arial;padding: 5px;text-align: center'><font color=white>Journal</font></td>
            <td bgcolor=#F8F8FF style='font-family:Arial;padding: 5px;text-align: center'>$row[journal]</td>
        </tr>
    </table>";

  }
  echo "</table>";

} else {
  echo "No results found.";
}

// Close connection
$conn->close();
?>