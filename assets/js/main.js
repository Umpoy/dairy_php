$(".toggleForms").on("click", function () {
    $("#signUpForm").toggle();
    $("#logInForm").toggle();
});

$("#dairy").bind("input propertychange", function () {
    $.ajax({
        method: "POST",
        url: "updateDatabase.php",
        data: { content: $("#dairy").val() }
    })
        .done(function (msg) {
            alert("Data Saved: " + msg);
        });
})