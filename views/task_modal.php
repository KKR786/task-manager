<div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskModalLabel">Create New Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="taskForm" action="../task_action.php" method="POST">
                    <input type="hidden" name="task_id" id="taskId">
                    <div class="form-group">
                        <label for="title">Task Title:</label>
                        <input type="text" id="title" name="title" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Task Description:</label>
                        <textarea id="description" name="description" class="form-control" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="due_date">Due Date:</label>
                        <input type="date" id="due_date" name="due_date" class="form-control">
                    </div>

                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="completed" name="completed" value="1">
                        <label class="form-check-label" for="completed">Mark as Completed</label>
                    </div>

                    <button type="submit" class="btn btn-primary" id="submitBtn">Create Task</button>
                </form>
            </div>
        </div>
    </div>
</div>