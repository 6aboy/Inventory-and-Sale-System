$(document).ready(function() {
    // <TABLES>
    // SUBJECTS TABLE
    $('#subjectsTable').DataTable({
        scrollY: '47.7vh',
        scrollCollapse: true, 
    });
    $('#subjectsTable').on('click', '.delete', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                document.location.href = href;
            }
        });
    });

    // USERS TABLE
    $('#usersTable').DataTable({
        scrollY: '47.7vh',
        scrollCollapse: true, 
    });
    $('#usersTable').on('click', '.delete', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                document.location.href = href;
            }
        });
    });

    $('#listsTable').DataTable({
        scrollY: '27.7vh',
        scrollCollapse: true, 
        lengthChange: false, 
        pageLength: 10,
        info: false, 
        dom: "<'top d-flex justify-content-between'f p>rt"
    });

    $('#listsTable').on('click', '.delete', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                document.location.href = href;
            }
        });
    });
})

// </TABLES>




// <SIDEBAR TOGGLE>
var arrows = document.querySelectorAll(".icon-link");
var sidebar = document.querySelector(".sidebar");
var menuToggle = document.querySelector(".bar");
var submenus = document.querySelectorAll(".sub-menu");
var ar = document.querySelectorAll(".arrow");
var navLists = document.querySelectorAll(".nav-list");


if (sidebar.classList.contains("close")) {
    submenus.forEach((submenu) => {
        submenu.style.maxHeight = "200px";
        submenu.style.transition = "0s"; 
        
    });
}

menuToggle.addEventListener("click", function () {
    sidebar.classList.toggle("close");

    ar.forEach((arrow) => {
        arrow.style.opacity = "0";
    });

    navLists.forEach((navList) => {
        navList.style.display = "none";
        navList.style.transition = "0.1s ease";
    });


    
    if (sidebar.classList.contains("close")) {
        document.querySelectorAll(".showMenu").forEach((element) => {
            element.classList.remove("showMenu");
        });

        submenus.forEach((submenu) => {
            submenu.style.maxHeight = "200px"; 
            submenu.style.transition = "0s"; 
        });
    } else {
        submenus.forEach((submenu) => {
            submenu.style.removeProperty("display"); 
            submenu.style.removeProperty("max-height"); 
            
        });
    }

    sidebar.addEventListener("transitionend", function () {
        if (!sidebar.classList.contains("close")) {
            ar.forEach((arrow) => {
                arrow.style.opacity = "1";
                arrow.style.transition = "0.1s ease";
            });
            navLists.forEach((navList) => {
                navList.style.display = "block";
            });
        }
        else {
            navLists.forEach((navList) => {
                navList.style.display = "block"; 
                navList.style.transition = "0.1s ease"; 
            });
        }
    });
    

    arrows.forEach(arrow => {
        arrow.addEventListener("click", (e) => {
            var arrowParent = e.target.closest("li");
            var submenu = arrowParent.querySelector(".sub-menu");

            if (!sidebar.classList.contains("close")) {

                arrows.forEach((arrow) => {
                    if (arrow !== e.target) {
                        arrow.parentElement.classList.remove("showMenu");
                        arrow.parentElement.querySelector(".sub-menu").style.transition = "0.5s ease";
                    }
                });


                arrowParent.classList.toggle("showMenu");

                if (arrowParent.classList.contains("showMenu")) {
                    submenu.style.removeProperty("transition"); 
                }
            }
        });
    });
});

// </SIDEBAR TOGGLE>

// <Generate Report For Books>
document.addEventListener("DOMContentLoaded", function() {
    var generate_report = document.getElementById('generate_report');
    var filter_bg = document.getElementById('filter_bg');
    var filter_data = document.getElementById('filter_data');
    var filter_all = document.getElementById('filter_all');
    var cancel_filter = document.getElementById('cancel_filter');
    var filter_form = document.getElementById('filter_form');

    generate_report.addEventListener('click', function () {
        generate_report.style.display = 'none';
        filter_bg.style.display = 'block';
    });

    filter_data.addEventListener('click', function () {
        filter_form.style.maxHeight = "500px";
        filter_data.style.display = 'none';
        filter_all.style.display = 'none';
        cancel_filter.style.display = 'block';
    });

    cancel_filter.addEventListener('click', function () {
        filter_form.style.maxHeight = "0";
        filter_data.style.display = 'block';
        filter_all.style.display = 'block';
        cancel_filter.style.display = 'none';
    });

    document.addEventListener('click', function (event) {
        if (!filter_bg.contains(event.target) && !generate_report.contains(event.target)) {
            generate_report.style.removeProperty('display');
            filter_bg.style.display = 'none';
            filter_data.style.display = 'block';
            filter_all.style.display = 'block';
            cancel_filter.style.display = 'none';
            filter_form.style.maxHeight = "0";
        }
    });
});
// </Generate Report For Books>

//  <Change Icons on Hover>
var viewButtons = document.querySelectorAll('.btn.btn-info');


viewButtons.forEach((button) => {
    var eyeIcon = button.querySelector('i'); 
    
    button.addEventListener('mouseover', () => {
    eyeIcon.classList.remove('fa-regular'); 
    eyeIcon.classList.add('fa-solid');     
    });

    button.addEventListener('mouseout', () => {
    eyeIcon.classList.remove('fa-solid');  
    eyeIcon.classList.add('fa-regular');  
    });
});

var editButtons = document.querySelectorAll('.btn.btn-warning');

editButtons.forEach((button) => {
    var editIcon = button.querySelector('i'); 
    
    button.addEventListener('mouseover', () => {
    editIcon.classList.remove('fa-regular'); 
    editIcon.classList.add('fa-solid');     
    });

    button.addEventListener('mouseout', () => {
    editIcon.classList.remove('fa-solid');  
    editIcon.classList.add('fa-regular');  
    });
});

var generateReportButton = document.querySelector('#generate_report');

var pdfIcon = generateReportButton.querySelector('i'); 

generateReportButton.addEventListener('mouseover', () => {
    pdfIcon.classList.remove('fa-regular'); 
    pdfIcon.classList.add('fa-solid');     
});

generateReportButton.addEventListener('mouseout', () => {
    pdfIcon.classList.remove('fa-solid');  
    pdfIcon.classList.add('fa-regular');  
});

var deleteButtons = document.querySelectorAll('.btn.btn-danger.delete');

deleteButtons.forEach((button) => {
    var deleteIcon = button.querySelector('i'); 
    
    button.addEventListener('mouseover', () => {
    deleteIcon.classList.remove('fa-regular'); 
    deleteIcon.classList.add('fa-solid');     
    });

    button.addEventListener('mouseout', () => {
    deleteIcon.classList.remove('fa-solid');  
    deleteIcon.classList.add('fa-regular');  
    });
});

var filterButtons = document.querySelectorAll('.btn.btn-warning.filter');

filterButtons.forEach((button) => {
    var filterIcon = button.querySelector('i'); 
    
    button.addEventListener('mouseover', () => {
    filterIcon.classList.remove('fa-regular'); 
    filterIcon.classList.add('fa-solid');     
    });

    button.addEventListener('mouseout', () => {
    filterIcon.classList.remove('fa-solid');  
    filterIcon.classList.add('fa-regular');  
    });
});
// </Change Icons on Hover>                                                        

