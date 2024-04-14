(function () {
    var todoListItems = [];

    /******************************************************************************************
     *
     *   updateToDB ()
     *  Arg: item object
     *   Desc: Calls laravel route to update the db
     *   return: item object
     */
    function updateToDB(item)
    {
        console.log("updateToDB");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //$.post("ajax.inc.php", {data:"action=event_view&" + item}).done(function(data){
        //    console.log(data);
        //    item.dbId = data.id;
        //});

        console.log("start ajax");
         $.ajax({
             type: "POST",
             url: "ajax.inc.php",
             data: "action=task_update&data=" + JSON.stringify(item),
             success: function(data){
                 console.log("test!!!!");
                 console.log(data);
                 //fx.boxin(data, modal);
             },
             error: function(msg) {
                 console.log("msg");
                 //modal.append(msg);
             }
        });

       // $.post("ajax.inc.php", {data: "action=task_update&data=" + JSON.stringify(item)}).done(function(data){
       //     console.log("hello");
       //     console.log(data);
       //     item.dbId = data.id;
       // });
        console.log("end ajax");
        return item;

    }

    function editTodo(index, text)
    {
        console.log("editTodo");
        let todo = getTodoById(index);
        if(todo)
        {
            todo.title = text;
            updateToDB(todo);
            saveList();
            redrawList();
        }

    }

    function inputEditItemBlurHandler(event)
    {
        console.log("editblurhandler");
        var input = event.target;
        var text = input.value.trim();
        var index = input.getAttribute('data-todo-id');
        if(text === '')
        {
            deleteTodo(index);
        }
        else
        {
            editTodo(index, text);
        }

    }

    function inputEditItemKeypressHandler(event)
    {
        console.log("keypress");
        if(event.keyCode === 13)
        {
            let input = event.target;
            let text = input.value.trim();
            let index = input.getAttribute('data-todo-id');
            if(text.value === '')
            {
                deleteTodo(index);
            }
            else
            {
                editTodo(index, text);
            }
        }
    }

    function getTodoIndexById(id)
    {
        console.log("getTodoIndexById");
        var i, l;
        for(i = 0,  l = todoListItems.length; i < l; i++)
        {
            if(todoListItems[i].id == id)
            {
                return i;
            }

        }
        return -1;
    }

    function deleteTodo(id)
    {
        console.log("deletetodo");
        let index = getTodoIndexById(id);
        if(index > -1)
        {
            let todo = todoListItems[index];
            //deleteFromDB(todo);
            todoListItems.splice(index,1);
            saveList();
            redrawList();
        }
    }

    function getTodoById(id)
    {
        console.log("getTodoById");
        let i, l;
        for(i = 0,  l = todoListItems.length; i < l; i++)
        {
            if(todoListItems[i].guid == id)
            {
                return todoListItems[i];
            }

        }
        return null;
    }

    function removeAllCompletedHandler(event)
    {
        console.log("removeAllCompletedHandler");
        let i,length;
        let newList = [];
        let toggle = event.target;
        for(i=0, length = todoListItems.length; i < length; i++)
        {
            if(!todoListItems[i].completed)
            {
                newList.push(todoListItems[i]);
            }
        }
        todoListItems = newList;
        saveList();
        redrawList();
    }

    function deleteClickHandler(event)
    {
        console.log("deleteClickHandler");
        let button = event.target;
        let index = button.getAttribute('data-todo-id');
        deleteTodo(index);
    }

    function editItemHandler(event)
    {
        console.log("editItemHandler");
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
        li.className ="editing";
        input.focus();
    }

    function migrateData() {
        console.log("migrate");
        var i, length, item;
        for (i = 0, length = todoListItems.length; i < length; i++) {
            item = todoListItems[i];
            if (typeof(item) == 'string') {
                todoListItems[i] = new todoItem(item, false);
            }
            if(typeof(item.id) === 'undefined')
            {
                todoListItems[i] = new todoItem(item.title, item.completed);
            }

        }

    }

    function checkboxChangeHandler(event) {
        console.log("checkboxChangeHandler");
        let checkbox = event.target;
        let index = checkbox.getAttribute('data-todo-id');
        let todo = getTodoById(index);
        todo.completed = checkbox.checked;
        updateToDB(todo);
        saveList();
        redrawList();
    }

    function saveList() {

        console.log("save");
        localStorage.setItem('todo-list', JSON.stringify(todoListItems));
    }

    function getUuid()
    {
        console.log("guid");
        let i = 0 , random = 0, uuid = '';
        for( i = 0; i < 32; i++)
        {
            random = Math.random() * 16 | 0;
            if(i === 8 || i === 12 || i === 16 || i === 20)
            {
                uuid += '-';
            }

            let part = (i === 16) ? (random & 3 | 8 ) : random;

            uuid += (i === 12) ? 4 : part.toString(16);
        }
        return uuid;
    }

    function todoItem(title, completed)
    {
        console.log("todoitem");
        this.title = title;
        this.completed = completed;
        this.guid = getUuid();
        this.priority = 0;
        this.dbId = -1;
    }

    function addToList(title) {
        console.log("addtolist");
        let todo = new todoItem(title, false);
        //todo = addToDB(todo);
        todoListItems.push(todo);
        saveList();
    }

    function newTodoKeyPressHandler(event) {
        console.log("newTodoKeyPressHandler");
        if (event.keyCode === 13)
        {
            let todoField = document.getElementById('new-todo');
            let text = todoField.value.trim();
            if(text !== '')
            {
                addToList(todoField.value);
                redrawList();
                todoField.value = "";
            }
        }
    }

    function toggleAllHandler(event)
    {
        console.log("toggleAllHandler");
        let index = 0, length = 0;
        let toggle = event.target;
        for(i=0, length =todoListItems.length;i < length; i++)
        {
            todoListItems[i].completed = toggle.checked;
        }
        saveList();
        redrawList();
    }

    function undocheckboxHandler(event)
    {
        console.log("undocheckboxHandler");
        let index = 0, length = 0;
        let toggle = event.target;
        for(i=0, length =todoListItems.length;i < length; i++)
        {
            todoListItems[i].completed = toggle.checked;
        }
        saveList();
        redrawList();
    }

    function redrawList()
    {
        console.log("redrawList");
        let incomplete= 0;
        let i;
        let list = document.getElementById('todo-list');
        let len = todoListItems.length;
        let filter = "all";
        list.innerHTML = "";

        for (i = 0; i < len; i++)
        {
            let todo = todoListItems[i];
            let item = document.createElement("li");
            item.guid = "li_" + todo.guid;
            todo.completed = todo.completed;
            if (todo.completed)
            {
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
            plabel.innerHTML="priority";
            plabel.className = "priorityLabel";
            plabel.addEventListener('dblclick', editItemHandler);
            plabel.setAttribute('data-todo-id', todo.guid);

            let inputPriority = document.createElement('input');
            inputPriority.type = "number";
            inputPriority.min=0;
            inputPriority.value = todo.priority;
            inputPriority.className = 'priority';
            if (todo.completed)
            {
                inputPriority.disabled = true;
            }
            else
                inputPriority.disabled = false;

            inputPriority.setAttribute('data-todo-id', todo.guid);
            //inputPriority.addEventListener('change', editPriorityHandler);

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
        let items = (incomplete == 1)? 'item' : 'items';
        todoCount.appendChild(document.createTextNode(" " + items + " left"  ));
        footer.appendChild(todoCount);

        if(len > 0 && (len - incomplete) > 0)
        {
            let filterList = document.createElement('ul');
            filterList.id = "filters";

            let allFilter = document.createElement("li");
            let allFilterLink = document.createElement("a");
            allFilterLink.href = "#all";
            allFilterLink.appendChild(document.createTextNode("All"));
            if(filter == 'All')
            {
                allFilterLink.className = "selected";
            }
            allFilter.appendChild(allFilterLink);
            filterList.appendChild(allFilter);

            let activeFilter = document.createElement("li");
            let activeFilterLink = document.createElement("a");
            activeFilterLink.appendChild(document.createTextNode('Active'));
            activeFilterLink.href = "#active";
            if(filter == 'active')
            {
                allFilterLink.className = "selected";
            }

            activeFilter.appendChild(activeFilterLink);
            filterList.appendChild(activeFilter);

            let completedFilter = document.createElement("li");
            let completedFilterLink = document.createElement('a');
            completedFilterLink.appendChild(document.createTextNode('Completed'));
            completedFilterLink.href = "#completed";
            if(filter == 'completed')
                completedFilterLink.className = "selected";
            completedFilter.appendChild(completedFilterLink);
            filterList.appendChild(completedFilter);

            footer.appendChild(filterList);
        }

        if(len > 0 && (len - incomplete > 0))
        {
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


    function finish(array)
    {
        console.log("finish");
        console.log(array);
        todoListItems = array;
        redrawList();
    }


    function fin(error)
    {
        console.log("fin");
        console.log(error);
    }

    function reloadList() {
        console.log("reloadList");
        let stored;

        let myPromise = new Promise(function(myResolve, myReject) {
            $.get("ajax.inc.php?action=getlist").done(function(data){
                stored = data;
                if(stored)
                {
                    myResolve(stored); // when successful
                }
                else
                    myReject("error");  // when error
            });
        });

        myPromise.then(
            function(value) {finish(value);},
            function(error) {fin(error);}
        );
    }

    window.addEventListener('load', windowLoadHandler, false);
    function windowLoadHandler() {
        console.log("load");
        reloadList();
        document.getElementById('toggle-all').addEventListener('change', toggleAllHandler, false);
        //console.log(document.getElementById('undo-all'));
        //document.getElementById('undo-all').addEventListener('change', undocheckboxHandler, false);
        document.getElementById('new-todo').addEventListener('keypress', newTodoKeyPressHandler, false);
    }
}());
