<?php
  include('partials\header.php');
  include('partials\sidebar.php');
  include('database\database.php');

    // Pagination logic
    $limit = 5; // Number of movies per page
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($page - 1) * $limit;
  

  $search_query = "";
  if (isset($_POST['query'])) {
    $search_query = $_POST['query'];
    $sql = "SELECT * FROM movie WHERE title LIKE '%$search_query%' OR director LIKE '%$search_query%' OR genre LIKE '%$search_query%' OR release_date LIKE '%$search_query%' ORDER BY ID DESC";
  } else {
$sql = "SELECT * FROM movie ORDER BY ID DESC LIMIT $limit OFFSET $offset";
  }

  $movie = $conn->query($sql);

  if (!$movie) {
    die("Error in query: ". $conn->error);
  }

  $status = "";
  if (isset($_SESSION['status'])){
    $status = $_SESSION['status'];
    unset($_SESSION['status']);//remove the session
  }


  // Your PHP BACK CODE HERE

?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Movie Information Management System</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item">Tables</li>
          <li class="breadcrumb-item active">General</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between">
                <div>
                  <h5 class="card-title">Movie Table</h5>
                </div>
                <div>
                <button type="button" class="btn btn-primary btn-sm mt-4 mx-3" data-bs-toggle="modal" data-bs-target="#addMovieModal">Add Movie</button>

<!-- ... (Existing code) ... -->

<!-- Add Movie Modal -->
<div class="modal fade" id="addMovieModal" tabindex="-1" aria-labelledby="addMovieModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addMovieModalLabel">Add Movie</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="add-movie-form" method="POST" action="database/create.php">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="director" class="form-label">Director:</label>
                        <input type="text" class="form-control" id="director" name="director" required>
                    </div>
                    <div class="mb-3">
                        <label for="genre" class="form-label">Genre</label>
                        <input type="text" class="form-control" id="genre" name="genre" required>
                    </div>
                    <div class="mb-3">
                        <label for="release_date" class="form-label">Release Date:</label>
                        <input type="date" class="form-control" id="release_date" name="release_date" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Movie</button>
                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
                </div>
              </div>

              <!-- Default Table -->
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Director</th>
                    <th scope="col">Genre</th>
                    <th scope="col">Release Date</th>
                    <th scope="col" class="text-center">Action</th>
                  </tr>
                </thead>
                
   <?php if ($movie->num_rows > 0):?>
    <?php 
    $counter = ($page - 1) * $limit + 1; // Initialize counter based on pagination
  ?>
  <?php while($row = $movie->fetch_assoc()):?>
    <tr>
    <td scope="row"><?php echo $counter++; ?></td> <!-- Auto-incrementing number -->
      <td><?php echo $row["title"];?></td>
      <td><?php echo $row["director"];?></td>
      <td><?php echo $row["genre"];?></td>
      <td><?php echo $row["release_date"];?></td>
      <td>
      <button class="btn btn-warning btn-sm edit-btn"
        data-id="<?php echo $row['id']; ?>"
        data-title="<?php echo $row['title']; ?>"
        data-director="<?php echo $row['director']; ?>"
        data-genre="<?php echo $row['genre']; ?>"
        data-release_date="<?php echo $row['release_date']; ?>"
        data-bs-toggle="modal" data-bs-target="#editModal">
    Edit
</button>

<button class="btn btn-primary btn-sm view-btn"
        data-title="<?php echo $row['title']; ?>"
        data-director="<?php echo $row['director']; ?>"
        data-genre="<?php echo $row['genre']; ?>"
        data-release_date="<?php echo $row['release_date']; ?>"
        data-bs-toggle="modal" data-bs-target="#viewModal">
    View
</button>

<!--EDIT-->
          <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModal">Movie Information</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                  <form id="edit-movie-form" method="POST" action="database/update.php">
                    <div class="container">
                      <div class="row">
                        <div class="col-12 mt-2">
                          <input type="hidden" name="update_id" value="<?php echo $row['id']; ?>">
                          <div class="col-12 mt-2">
                            <label for="title">TITLE</label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="Enter title" value="<?php echo $row['title']; ?>" required>
                          </div>
                          <div class="col-12 mt-2">
                            <label for="director">Director</label>
                            <input type="text" name="director" id="director" class="form-control" placeholder="Enter director name" value="<?php echo $row['director']; ?>" required>
                          </div>
                          <div class="col-12 mt-2">
                            <label for="genre">GENRE</label>
                            <input type="text" name="genre" id="genre" class="form-control" placeholder="Enter genre" value="<?php echo $row['genre']; ?>" required>
                          </div>
                          <div class="col-12 mt-2">
                            <label for="release_date">Release Date</label>
                            <input type="date" name="release_date" id="release_date" class="form-control" placeholder="Enter Release Date" value="<?php echo $row['release_date']; ?>" required>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          
         
          
<!--view-->
          <div class="modal fade" id="viewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="viewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <form action="database/create.php" method="POST">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="viewLabel">Movie Information</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="container">
                      <div class="row">
                        <div class="col-12 mt-2">
                          <label for="title">TITLE</label>
                          <input type="text" name="title" id="title" class="form-control" placeholder="Enter title" value="<?php echo $row['title']; ?>" disabled>
                        </div>
                        <div class="col-12 mt-2">
                          <label for="director">Director</label>
                          <input type="text" name="director" id="director" class="form-control" placeholder="Enter director name" value="<?php echo $row['director']; ?>" disabled>
                        </div>
                        <div class="col-12 mt-2">
                          <label for="genre">GENRE</label>
                          <input type="text" name="genre" id="genre" class="form-control" placeholder="Enter genre" value="<?php echo $row['genre']; ?>" disabled>
                        </div>
                        <div class="col-12 mt-2">
                          <label for="release_date">Release Date</label>
                          <input type="text" name="release_date" id="release_date" class="form-control" placeholder="Enter release_date" value="<?php echo $row['release_date']; ?>" disabled>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <button class="btn btn-danger btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
          
          
          
<!--DELETE-->
          <div class="modal fade" id="deleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteLabel<?php echo $row['id']; ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <form action="database/delete.php" method="POST">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5 text-danger" id="deleteLabel">Delete Movie</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body text-center">
                    <h1 class="text-danger" style="font-size:50px"><strong>!</strong></h1>
                    <h3>Are you sure you want to delete this movie?</h3>
                    <h4>This action cannot be undone</h4>
                  </div>
                  <div class="modal-footer d-flex justify-content-center">
                  <input type="hidden" name="ID" value="<?php echo $row['id']; ?>">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </td>
    </tr>
  <?php endwhile;?>
<?php else: ?>
  <tr>
    <td colspan="5" class="text-center">No record found</td>
  </tr>
<?php endif;?>
                </tbody>
              </table>
              <!-- End Default Table Example -->
            </div>
            
            
            </table> <!-- Close the movie table -->
</div>

<div class="mx-4">
    <?php
    // Count total number of movies
    $total_sql = "SELECT COUNT(*) FROM movie";
    $total_result = $conn->query($total_sql);
    $total_row = $total_result->fetch_array();
    $total_movies = $total_row[0];

    // Calculate total pages
    $total_pages = ceil($total_movies / $limit);
    ?>

    <nav aria-label="Page navigation">
        <ul class="pagination">
            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= max(1, $page - 1) ?>">Previous</a>
            </li>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= min($total_pages, $page + 1) ?>">Next</a>
            </li>
        </ul>
    </nav>
</div>


        </div>

      </div>

      
    </section>

    

  </main><!-- End #main -->

  <script>
  document.addEventListener("DOMContentLoaded", function () {
    // Handle Edit Button Click
    document.querySelectorAll(".edit-btn").forEach((button) => {
      button.addEventListener("click", function () {
        document.querySelector("#editModal input[name='update_id']").value = this.getAttribute("data-id");
        document.querySelector("#editModal input[name='title']").value = this.getAttribute("data-title");
        document.querySelector("#editModal input[name='director']").value = this.getAttribute("data-director");
        document.querySelector("#editModal input[name='genre']").value = this.getAttribute("data-genre");
        document.querySelector("#editModal input[name='release_date']").value = this.getAttribute("data-release_date");
      });
    });

    // Handle View Button Click
    document.querySelectorAll(".view-btn").forEach((button) => {
      button.addEventListener("click", function () {
        document.querySelector("#viewModal input[name='title']").value = this.getAttribute("data-title");
        document.querySelector("#viewModal input[name='director']").value = this.getAttribute("data-director");
        document.querySelector("#viewModal input[name='genre']").value = this.getAttribute("data-genre");
        document.querySelector("#viewModal input[name='release_date']").value = this.getAttribute("data-release_date");
      });
    });

    // Handle Delete Button Click
    document.querySelectorAll(".delete-btn").forEach((button) => {
      button.addEventListener("click", function () {
        document.querySelector("#deleteModal input[name='ID']").value = this.getAttribute("data-id");
      });
    });
  });
</script>

<?php
  include('partials\footer.php');
?>