<h2>Manage Courses</h2>
<?php foreach ($courses as $course): ?>
    <p><?= esc($course['name']) ?></p>
<?php endforeach; ?>

<h1>Manage Courses</h1>

<?php if(!empty($courses)): ?>
    <ul id="courseList">
        <?php foreach($courses as $course): ?>
            <li class="course-item">
                <?= esc($course['name']) ?> - <?= esc($course['description']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No courses available.</p>
<?php endif; ?>

<!-- 🔍 SEARCH BAR -->
<div class="container mb-3 mt-4">
    <form id="searchForm">
        <div class="input-group">
            <input type="text" name="keyword" id="keyword" class="form-control"
                   placeholder="Search course name or code...">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>
</div>

<!-- 🔥 WHERE YOU SAID: INSERT THE NEW CODE HERE -->
<!-- CLIENT-SIDE FILTERING + AJAX SEARCH -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // ⭐ STEP 5: CLIENT-SIDE FILTERING (Instant Filter)
    $("#keyword").on("keyup", function() {
        let value = $(this).val().toLowerCase();

        $("#courseList .course-item").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    // ⭐ STEP 4 + 7: AJAX SERVER-SIDE SEARCH
    $("#searchForm").on("submit", function(e) {
        e.preventDefault();

        let keyword = $("#keyword").val();

        $.ajax({
            url: "/course/search",
            method: "GET",
            data: { keyword: keyword },
            success: function(response) {
                let list = $("#courseList");
                list.empty();

                if (response.length === 0) {
                    list.append("<li>No results found.</li>");
                    return;
                }

                response.forEach(function(course) {
                    list.append(`
                        <li class="course-item">
                            ${course.course_name} - ${course.description}
                        </li>
                    `);
                });
            }
        });
    });
</script>
