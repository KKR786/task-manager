$(document).ready(function () {
  $("#taskForm").submit(function (event) {
    event.preventDefault();

    let formData = {
      task_id: $("#taskId").val(),
      title: $("#title").val(),
      description: $("#description").val(),
      due_date: $("#due_date").val(),
      completed: $("#completed").prop("checked") ? 1 : 0,
    };

    $.ajax({
      url: "../task_action.php",
      type: "POST",
      data: formData,
      dataType: "json",
      success: function (response) {
        if (response.status === "success") {
          alert(response.message);

          $("#taskModal").modal("hide");

          $("#taskForm")[0].reset();

          location.reload();
        } else {
          alert(response.message);
        }
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
        alert("There was an error processing your request: " + error);
      },
    });
  });

  $(".edit-task").on("click", function (e) {
    e.preventDefault();

    const taskId = $(this).data("id");

    $.ajax({
      url: "../task_action.php",
      type: "GET",
      data: { action: "get_task", task_id: taskId },
      dataType: "json",
      success: function (task) {
        $("#taskModalLabel").text("Edit Task");
        $("#title").val(task.title);
        $("#description").val(task.description);
        $("#due_date").val(task.due_date);
        $("#taskId").val(task.id);
        $("#completed").prop("checked", task.completed == 1);
        $("#submitBtn").text("Update Task");
      },
      error: function () {
        alert("Failed to fetch task details");
      },
    });
  });

  $("#createBtn").on("click", function () {
    $("#taskModalLabel").text("Create New Task");
    $("#taskForm")[0].reset();
    $("#taskId").val("");
    $("#submitBtn").text("Create Task");
  });

  $(document).on("click", ".share-facebook", function () {
    const taskId = $(this).data("id");
    const platform = $(this).data("platform");

    $.ajax({
      url: "../share_task.php",
      type: "POST",
      data: { taskId: taskId, platform: platform },
      success: function (response) {
        alert(JSON.stringify(response));
      },
      error: function (response) {
        alert(JSON.stringify(response));
      },
    });
  });

  let googleAccessToken = null;

  function initiateGoogleOAuth() {
    const clientId = GOOGLE_CLIENT_ID;
    const redirectUri = GOOGLE_REDIRECT_URI;
    const scope = "https://www.googleapis.com/auth/business.manage";
    const authUrl = `https://accounts.google.com/o/oauth2/auth?${new URLSearchParams(
      {
        client_id: clientId,
        redirect_uri: redirectUri,
        scope: scope,
        response_type: "code",
        access_type: "offline",
        prompt: "consent",
      }
    )}`;

    window.location.href = authUrl;
  }

  $(document).on("click", ".share-google", async function () {
    const taskId = $(this).data("id");
    if (!googleAccessToken) {
      const isAuthorized = await checkGoogleAuthorization();
      if (!isAuthorized) {
        alert("Please authorize Google My Business first.");
        initiateGoogleOAuth();
        return;
      }
    }

    $.ajax({
      url: "../share_task.php",
      type: "POST",
      data: {
        taskId: taskId,
        platform: "google",
        accessToken: googleAccessToken,
      },
      success: function (response) {
        alert("Task shared to Google My Business!");
      },
      error: function () {
        alert("Error posting to Google My Business.");
      },
    });
  });

  async function checkGoogleAuthorization() {
    try {
      const response = await fetch("../check_google_auth.php");
      const data = await response.json();

      if (data.status === "success") {
        googleAccessToken = data.accessToken;
        return true;
      } else {
        return false;
      }
    } catch (error) {
      console.error("Error checking Google authorization:", error);
      return false;
    }
  }
});

function confirmDelete(taskId) {
  if (confirm("Are you sure you want to delete this task?")) {
    $.ajax({
      url: "../task_action.php",
      type: "POST",
      data: {
        action: "delete",
        task_id: taskId,
      },
      dataType: "json",
      success: function (response) {
        if (response.status === "success") {
          alert(response.message);
          location.reload();
        } else {
          alert(response.message);
        }
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
        alert("There was an error deleting the task: " + error);
      },
    });
  }
}
