<!DOCTYPE html>
<html>

<head>
    <title>Items</title>
    <!-- Link to Bootstrap CSS -->
<!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">-->
</head>

<body class="bg-dark text-light">

    <div class="container-fluid bg-transparent text-light">
       
        <div class="row">
            <div class="col-7 mx-auto">

              <h3 class="display-3">
                  <!-- <a href="?viewitem=2" class="btn btn-primary-outline">
                      <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-grid-3x3-gap" viewBox="0 0 16 16">
                          <path d="M4 2v2H2V2h2zm1 12v-2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1zm0-5V7a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1zm0-5V2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1zm5 10v-2a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1zm0-5V7a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1zm0-5V2a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1zM9 2v2H7V2h2zm5 0v2h-2V2h2zM4 7v2H2V7h2zm5 0v2H7V7h2zm5 0h-2v2h2V7zM4 12v2H2v-2h2zm5 0v2H7v-2h2zm5 0v2h-2v-2h2zM12 1a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1h-2zm-1 6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V7zm1 4a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1h-2z" />
                      </svg>
                  </a> -->
                  Items
              </h3>
              <form action="?view_item" method="POST">  
              </form>
              
              <?php 
              $items = query($conn, "SELECT i.item_id, p.price_id, c.cat_id, s.size_id, i.item_name, i_price, i.date_added, i.item_file, c.category_name, c.category_description, s.size, p.item_price, c.category_name, c.cat_file, i.item_stats, c.cat_stats
              FROM price p
              JOIN category c ON p.cat_id = c.cat_id
              JOIN item i ON i.item_id = p.item_id
              JOIN sizes s ON s.size_id = p.size_id
              WHERE i.item_stats='A' ORDER BY i.item_id ASC") ;
              ?>
              
              <table class="table text-light" style="height: 100px;overflow:auto">
                    <tr>
                        <th>Item Thumbnail</th>
                        <th>Item Name</th>
                        <th>Category Name</th>
                        <th>Size </th>
                        <th>Price</th>
                        <th>Date Added</th>
                        <th>Status</th>
                        <th>Action </th>
                    </tr>
                  <?php
                
                  $active="<span class='text-success'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-patch-check-fill' viewBox='0 0 16 16'>
  <path d='M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01-.622-.636zm.287 5.984-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7 8.793l2.646-2.647a.5.5 0 0 1 .708.708z'/>
</svg> </span>";
                  $inactive="<span class='text-secondary'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-patch-minus' viewBox='0 0 16 16'>
  <path fill-rule='evenodd' d='M5.5 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H6a.5.5 0 0 1-.5-.5z'/>
  <path d='m10.273 2.513-.921-.944.715-.698.622.637.89-.011a2.89 2.89 0 0 1 2.924 2.924l-.01.89.636.622a2.89 2.89 0 0 1 0 4.134l-.637.622.011.89a2.89 2.89 0 0 1-2.924 2.924l-.89-.01-.622.636a2.89 2.89 0 0 1-4.134 0l-.622-.637-.89.011a2.89 2.89 0 0 1-2.924-2.924l.01-.89-.636-.622a2.89 2.89 0 0 1 0-4.134l.637-.622-.011-.89a2.89 2.89 0 0 1 2.924-2.924l.89.01.622-.636a2.89 2.89 0 0 1 4.134 0l-.715.698a1.89 1.89 0 0 0-2.704 0l-.92.944-1.32-.016a1.89 1.89 0 0 0-1.911 1.912l.016 1.318-.944.921a1.89 1.89 0 0 0 0 2.704l.944.92-.016 1.32a1.89 1.89 0 0 0 1.912 1.911l1.318-.016.921.944a1.89 1.89 0 0 0 2.704 0l.92-.944 1.32.016a1.89 1.89 0 0 0 1.911-1.912l-.016-1.318.944-.921a1.89 1.89 0 0 0 0-2.704l-.944-.92.016-1.32a1.89 1.89 0 0 0-1.912-1.911l-1.318.016z'/>
</svg></span>";
                  foreach($items as $item){
                    echo "<tr>"; ?>
                      <td><img width="100px" class="object-fit-cover border border-1 border-warning" src="../img/<?php echo $item['cat_file'];?>" alt="<?php echo $item['item_name']; ?>"></td>
                      <?php echo "<td>" . ucwords($item['item_name']) . "</td>"; 
                      echo "<td>" . $item['category_name'] . "</td>";
                      echo "<td>" . $item['size'] . "</td>";
                      echo "<td>" . $item['item_price'] . "</td>";
                      echo "<td>" . $item['date_added'] . "</td>";

                    echo "<td>" . ($item['item_stats']=='A'? $active :$inactive) . "</td>"?>


                    <td> <a href="?updateitem=<?php echo $item['item_id'];?>" class="btn btn-primary"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16"><path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/><path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/></svg> </a> </td>
                    <?php if($item['item_stats'] == 'A') { ?>      
                    <td> <a href="?viewitem=1&deacitem=<?php echo $item['item_id'];?>" class="btn btn-outline-success "> 
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-toggle-off" viewBox="0 0 16 16"><path d="M11 4a4 4 0 0 1 0 8H8a4.992 4.992 0 0 0 2-4 4.992 4.992 0 0 0-2-4h3zm-6 8a4 4 0 1 1 0-8 4 4 0 0 1 0 8zM0 8a5 5 0 0 0 5 5h6a5 5 0 0 0 0-10H5a5 5 0 0 0-5 5z"/></svg></a> </td>
                    <?php }
                    if($item['item_stats'] == 'I'){ ?>
                    <td> <a tooltip="Reactivate" href="?viewitem=1&reacitem=<?php echo $item['cat_id'];?>" class="btn btn-outline-danger "> 
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-toggle-on" viewBox="0 0 16 16"><path d="M5 3a5 5 0 0 0 0 10h6a5 5 0 0 0 0-10H5zm6 9a4 4 0 1 1 0-8 4 4 0 0 1 0 8z"/></svg>
                                    </a> </td>
                  <?php }
                       echo "</tr>";


                       
                  } ?>

              </table>
              
            </div>
        </div>


    </div>

</body>

</html>