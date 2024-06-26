(function () {
    //main array
    /**
     * ©2024 David Martin. All Rights Reserve.
     */
    let todoListItems = [];

    /******************************************************************************************
     *
     *   deleteFromDB ()
     *  arg: item object
     *   desc: calls laravel route to delete a task item.
     */
    function deleteFromDB(item) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: "ajax.inc.php",
            data: "action=task_delete&data=" + JSON.stringify(item),
            success: function (data) {

            },
            error: function (msg) {
                console.log(msg.responseText);
            }
        });
    }

    /******************************************************************************************
     *
     *   updateToDB ()
     *  Arg: item object
     *   Desc: Calls laravel route to update the db
     *   return: item object
     */
    function updateToDB(item) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: "ajax.inc.php",
            data: "action=task_update&data=" + JSON.stringify(item),
            success: function (data) {

            },
            error: function (msg) {
                console.log(msg.responseText);
            }
        });

        return item;
    }

    /******************************************************************************************
     *
     *   editTodo ()****
     *  Arg: integer index, string text
     *   Desc: get the item from the list, update the text, update the db and redraw the list.
     */
    function editTodo(index, text) {
        let todo = getTodoById(index);
        if (todo) {
            todo.title = text;
            updateToDB(todo);
            redrawList();
        }

    }

    /******************************************************************************************
     *
     *   inputEditItemBlurHandler ()*****
     *  arg:event obj
     *   desc: handle for editing tasks. If the user backspaces the task, delete the task. Otherwise call edit
     */
    function inputEditItemBlurHandler(event) {
        let input = event.target;
        let text = input.value.trim();
        let index = input.getAttribute('data-todo-id');
        if (text === '') {
            deleteTodo(index);
        } else {
            editTodo(index, text);
        }

    }

    /******************************************************************************************
     *
     * inputEditItemKeypressHandler ()****
     *  arg:event obj
     *   desc: handle for editing tasks. If the user backspaces the task, delete the task. Otherwise call edit
     */
    function inputEditItemKeypressHandler(event) {
        if (event.keyCode === 13) {
            let input = event.target;
            let text = input.value.trim();
            let index = input.getAttribute('data-todo-id');
            if (text.value === '') {
                deleteTodo(index);
            } else {
                editTodo(index, text);
            }
        }
    }

    /******************************************************************************************
     *
     *   getTodoIndexById()****
     *    Arg: guid string
     *   Desc: return index of the todo array
     *   return: int
     */
    function getTodoIndexById(id) {
        let i, l;
        for (i = 0, l = todoListItems.length; i < l; i++) {
            if (todoListItems[i].guid == id) {
                return i;
            }

        }
        return -1;
    }

    /******************************************************************************************
     *
     *   deleteTodo ()*****
     *   Arg: task id
     *   Desc: delete todo by id given and update the db and redraw the list.
     */
    function deleteTodo(id) {
        let index = getTodoIndexById(id);
        if (index > -1) {
            let todo = todoListItems[index];
            deleteFromDB(todo);
            todoListItems.splice(index, 1);
            redrawList();
        }
    }

    /******************************************************************************************
     *
     *   getTodoById ()*****
     *  Arg: guid string
     *   desc: utility function to return task object given the guid
     *   return todo object/null
     */
    function getTodoById(id) {
        let i, l;
        for (i = 0, l = todoListItems.length; i < l; i++) {
            if (todoListItems[i].guid == id) {
                return todoListItems[i];
            }

        }
        return null;
    }

    /******************************************************************************************
     *
     *   removeAllCompletedHandler ()******
     *  Arg: event obj
     *   desc: removes all tasks that tagged complete
     */
    function removeAllCompletedHandler(event) {
        let i, length;
        let newList = [];
        let toggle = event.target;
        for (i = 0, length = todoListItems.length; i < length; i++) {
            if (!todoListItems[i].completed) {
                newList.push(todoListItems[i]);
            }
        }
        todoListItems = newList;
        redrawList();
    }

    /******************************************************************************************
     *
     *   deleteClickHandler ()****
     *  Arg: event object
     *   Desc: delete button handler. Gets the guid of the task and send to the delete function
     */
    function deleteClickHandler(event) {
        let button = event.target;
        let index = button.getAttribute('data-todo-id');
        deleteTodo(index);
    }

    /******************************************************************************************
     *
     *   editItemHandler ()****
     *  Arg: event obj
     *   Desc: event handle for setting up the input visually so allow task title editing.
     */
    function editItemHandler(event) {
        let label = event.target;
        let index = label.getAttribute('data-todo-id');
        let todo = getTodoById(index);
        let li = document.getElementById('li_' + index);
        let input = document.createElement('input');
        input.setAttribute('data-todo-id', index);
        input.className = "edit";
        input.value = todo.title;
        input.addEventListener('keypress', inputEditItemKeypressHandler);
        input.addEventListener('blur', inputEditItemBlurHandler);
        li.appendChild(input);
        li.className = "editing";
        input.focus();
    }

    /******************************************************************************************
     *
     *   checkboxChangeHandler ()*****
     *  Arg: event arg
     *   Desc: event handler for the checks next to each tasks
     */
    function checkboxChangeHandler(event) {
        let checkbox = event.target;
        let index = checkbox.getAttribute('data-todo-id');
        let todo = getTodoById(index);
        todo.completed = checkbox.checked;
        updateToDB(todo);
        redrawList();
    }

    /******************************************************************************************
     *
     *   getUuid ()*****
     *
     *  Desc: returns the guid string
     *  return: string
     */
    function getUuid() {
        let i = 0, random = 0, uuid = '';
        for (i = 0; i < 32; i++) {
            random = Math.random() * 16 | 0;
            if (i === 8 || i === 12 || i === 16 || i === 20) {
                uuid += '-';
            }

            let part = (i === 16) ? (random & 3 | 8) : random;

            uuid += (i === 12) ? 4 : part.toString(16);
        }
        return uuid;
    }

    /******************************************************************************************
     *
     *  todoItem ()*****
     *  Arg: string title and bool completed
     *   desc: used to return an todoItem object
     *
     */
    function todoItem(title, completed) {
        this.title = title;
        this.completed = completed;
        this.guid = getUuid();
        this.priority = 0;
        this.dbId = -1;
    }

    /******************************************************************************************
     *
     *   addToDB ()
     *  arg: item object
     *   desc: calls the laraval route to add a new itmem to the list
     *   return: object
     */
    function addToDB(item) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: "ajax.inc.php",
            data: "action=task_add&data=" + JSON.stringify(item),
            success: function (data) {

            },
            error: function (msg) {
                console.log(msg.responseText);
            }
        });

        return item;
    }

    /******************************************************************************************
     *
     *   addToList ()*****
     *  Arg: title name of task
     *   description: create a new todo object, add to db, and add to the js array
     */
    function addToList(title) {
        let todo = new todoItem(title, false);
        addToDB(todo);
        todoListItems.push(todo);
    }

    /******************************************************************************************
     *
     *   newTodoKeyPressHandler ()****
     *  arg:event object
     *   desc: handler function when a new task in entered
     */
    function newTodoKeyPressHandler(event) {
        if (event.keyCode === 13) {
            let todoField = document.getElementById('new-todo');
            let text = todoField.value.trim();
            if (text !== '') {
                addToList(todoField.value);
                redrawList();
                reloadList();

                todoField.value = "";
            }
        }
    }

    /******************************************************************************************
     *
     *   toggleAllHandler ()*****
     *
     *   desc: handle function to check off all the tasks in the list
     */
    function toggleAllHandler(event) {
        let index = 0, length = 0;
        let toggle = event.target;
        for (i = 0, length = todoListItems.length; i < length; i++) {
            todoListItems[i].completed = toggle.checked;
        }
        redrawList();
    }

    /******************************************************************************************
     *
     *   undocheckboxHandler ()*****
     *  arg: event object
     *   desc: handle function to uncheck off all the tasks in the list
     */
    function undocheckboxHandler(event) {
        let index = 0, length = 0;
        let toggle = event.target;
        for (i = 0, length = todoListItems.length; i < length; i++) {
            todoListItems[i].completed = toggle.checked;
        }
        redrawList();
    }

    /******************************************************************************************
     *
     *   editPriorityHandler ()
     *  Arg: event obj
     *   Desc: event handler for the priority input on each task and updates the database on each change
     */
    function editPriorityHandler(event) {
        let priority = event.target;
        let index = priority.getAttribute('data-todo-id');
        let todo = getTodoById(index);
        todo.priority = priority.value;
        updateToDB(todo);
        reloadList();
        redrawList();
    }

    /******************************************************************************************
     *
     *   redrawList()*****
     *
     *   Desc: Rebuilds the task list UI
     */
    function redrawList() {
        let incomplete = 0;
        let i;
        let list = document.getElementById('todo-list');
        let len = todoListItems.length;
        let filter = "all";
        list.innerHTML = "";

        for (i = 0; i < len; i++) {
            let todo = todoListItems[i];
            let item = document.createElement("li");
            item.id = "li_" + todo.guid;
            todo.completed = todo.completed;
            if (todo.completed) {
                item.className += "completed";
            }

            let checkbox = document.createElement('input');
            checkbox.className = "toggle";
            checkbox.type = "checkbox";
            checkbox.addEventListener('change', checkboxChangeHandler);
            checkbox.checked = todo.completed;
            checkbox.setAttribute('data-todo-id', todo.guid);

            let plabel = document.createElement('label');
            plabel.appendChild(document.createTextNode(todo.title));
            plabel.innerHTML = "priority";
            plabel.className = "priorityLabel";
            plabel.addEventListener('dblclick', editItemHandler);
            plabel.setAttribute('data-todo-id', todo.guid);

            let inputPriority = document.createElement('input');
            inputPriority.type = "number";
            inputPriority.min = 0;
            inputPriority.value = todo.priority;
            inputPriority.className = 'priority';
            if (todo.completed) {
                inputPriority.disabled = true;
            } else
                inputPriority.disabled = false;

            inputPriority.setAttribute('data-todo-id', todo.guid);
            inputPriority.addEventListener('change', editPriorityHandler);

            let label = document.createElement('label');
            label.appendChild(document.createTextNode(todo.title));
            label.addEventListener('dblclick', editItemHandler);
            label.setAttribute('data-todo-id', todo.guid);

            let deleteButton = document.createElement('button');
            deleteButton.className = 'destroy';
            deleteButton.setAttribute('data-todo-id', todo.guid);
            deleteButton.addEventListener('click', deleteClickHandler);


            let divDisplay = document.createElement('div');
            divDisplay.className = "view";
            divDisplay.appendChild(checkbox);

            divDisplay.appendChild(label);
            divDisplay.appendChild(plabel);
            divDisplay.appendChild(inputPriority);
            divDisplay.appendChild(deleteButton);
            item.appendChild(divDisplay);
            list.appendChild(item);
        }

        document.getElementById('toggle-all').checked = 0;

        let footer = document.getElementById('footer');
        footer.innerHTML = "";
        let todoCount = document.createElement('span');
        todoCount.id = "todo-count";
        let count = document.createElement('strong');
        count.appendChild(document.createTextNode(incomplete));
        todoCount.appendChild(count);
        let items = (incomplete == 1) ? 'item' : 'items';
        todoCount.appendChild(document.createTextNode(" " + items + " left"));
        footer.appendChild(todoCount);

        if (len > 0 && (len - incomplete) > 0) {
            let filterList = document.createElement('ul');
            filterList.id = "filters";

            let allFilter = document.createElement("li");
            let allFilterLink = document.createElement("a");
            allFilterLink.href = "#all";
            allFilterLink.appendChild(document.createTextNode("All"));
            if (filter == 'All') {
                allFilterLink.className = "selected";
            }
            allFilter.appendChild(allFilterLink);
            filterList.appendChild(allFilter);

            let activeFilter = document.createElement("li");
            let activeFilterLink = document.createElement("a");
            activeFilterLink.appendChild(document.createTextNode('Active'));
            activeFilterLink.href = "#active";
            if (filter == 'active') {
                allFilterLink.className = "selected";
            }

            activeFilter.appendChild(activeFilterLink);
            filterList.appendChild(activeFilter);

            let completedFilter = document.createElement("li");
            let completedFilterLink = document.createElement('a');
            completedFilterLink.appendChild(document.createTextNode('Completed'));
            completedFilterLink.href = "#completed";
            if (filter == 'completed')
                completedFilterLink.className = "selected";
            completedFilter.appendChild(completedFilterLink);
            filterList.appendChild(completedFilter);

            footer.appendChild(filterList);
        }

        if (len > 0 && (len - incomplete > 0)) {
            let button = document.createElement('button');
            button.id = 'clear-completed';
            button.appendChild(document.createTextNode("Clear completed (" + (len - incomplete) + ")"));
            button.addEventListener('click', removeAllCompletedHandler, false);
            footer.appendChild(button);
        }

        let undo_button = document.createElement('button');
        undo_button.id = 'undo-all';
        undo_button.setAttribute('data-todo-id', 0);
        undo_button.appendChild(document.createTextNode("Undo All(" + (len - incomplete) + ")"));
        undo_button.addEventListener('click', undocheckboxHandler, false);
        footer.appendChild(undo_button);
    }

    /******************************************************************************************
     *
     *   finish ()
     *  Arg: array input
     *   Desc: replace the current todo array after successful promise and redraw the list
     */
    function finish(array) {
        todoListItems = array;
        redrawList();
    }

    /******************************************************************************************
     *
     *   fin()
     *  Arg: error message
     *   Desc: function to handle post failed promise
     */
    function fin(error) {
        console.log(error);
    }

    /******************************************************************************************
     *
     *   reloadList ()*****
     *  Desc: gets the array of tasks from backend and utilizes a promise to wait for the data.
     */
    function reloadList() {
        let stored;

        let myPromise = new Promise(function (myResolve, myReject) {
            $.get("ajax.inc.php?action=getlist").done(function (data) {
                stored = data;
                if (stored.data) {
                    myResolve(stored.data); // when successful
                } else
                    myReject(stored.error);  // when error
            });
        });

        myPromise.then(
            function (value) {
                finish(value);
            },
            function (error) {
                fin(error);
            }
        );
    }

    /******************************************************************************************
     *
     *   windowLoadHandler ()
     *  arg:
     *   desc: handle function to reload the task list and set event listener to the toggle all button and the
     *   new task input
     */
    window.addEventListener('load', windowLoadHandler, false);

    function windowLoadHandler() {
        reloadList();
        document.getElementById('toggle-all').addEventListener('change', toggleAllHandler,
            false);
        document.getElementById('new-todo').addEventListener('keypress', newTodoKeyPressHandler,
            false);
    }
}());
