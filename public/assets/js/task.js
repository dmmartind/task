(function () {
    var todoListItems = [];

    function editTodo(index, text)
    {
        console.log(editTodo);
        var todo = getTodoById(index);
        //todoListItems[index].title = text;
        if(todo)
        {
            todo.title = text;
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
        console.log("editkeypresshandler");
        if(event.keyCode === 13)
        {
            var input = event.target;
            var text = input.value.trim();
            var index = input.getAttribute('data-todo-id');
            //input.removeEventListener('blur', inputEditItemBlurHandler);
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

    function deleteTodo(index)
    {
        console.log("deleteTodo");
        var index = getTodoIndexById(index);
        if(index > -1)
        {
            console.log("------------");
            console.log(index);
            console.log(todoListItems);
            console.log("------------");
            todoListItems.splice(index,1);
            saveList();
            redrawList();
        }
    }

    function getTodoById(id)
    {
        var i, l;
        for(i = 0,  l = todoListItems.length; i < l; i++)
        {
            if(todoListItems[i].id == id)
            {
                return todoListItems[i];
            }

        }
        return null;
    }

    function removeAllCompletedHandler(event)
    {
        var i,length;
        var newList = [];
        var toggle = event.target;
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
        console.log("delete handle");
        var button = event.target;
        var index = button.getAttribute('data-todo-id');
        deleteTodo(index);
    }

    function editItemHandler(event)
    {
        console.log("edititemhandler");
        var label = event.target;
        var index = label.getAttribute('data-todo-id');
        var todo = getTodoById(index);
        var li = document.getElementById('li_' + index);
        console.log(label);
        console.log(index);
        console.log(todo);
        console.log(li);
        var input = document.createElement('input');
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
        console.log("checkboxchangehandle");
        var checkbox = event.target;
        console.log(checkbox);
        var index = checkbox.getAttribute('data-todo-id');
        console.log(index);
        var todo = getTodoById(index);
        console.log(todo);
        todo.completed = checkbox.checked;
        saveList();
        redrawList();
    }

    function saveList() {

        console.log("save");
        localStorage.setItem('todo-list', JSON.stringify(todoListItems));
    }

    function getUuid()
    {
        console.log("getUuid");
        var i = 0 , random = 0, uuid = '';
        for( i = 0; i < 32; i++)
        {
            random = Math.random() * 16 | 0;
            if(i === 8 || i === 12 || i === 16 || i === 20)
            {
                uuid += '-';
            }

            var part = (i === 16) ? (random & 3 | 8 ) : random;

            uuid += (i === 12) ? 4 : part.toString(16);
        }
        return uuid;
    }

    function todoItem(title, completed)
    {
        console.log("todo");
        this.title = title;
        this.completed = completed;
        this.id = getUuid();
    }

    function addToList(title) {
        console.log("add");
        var todo = new todoItem(title, false);
        todoListItems.push(todo);
        saveList();
        //localStorage.setItem('todo-list', JSON.stringify(todoListItems));
    }

    function newTodoKeyPressHandler(event) {
        console.log("newtodo key handle");
        if (event.keyCode === 13)
        {
            var todoField = document.getElementById('new-todo');
            var text = todoField.value.trim();
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
        var index = 0, length = 0;
        var toggle = event.target;
        for(i=0, length =todoListItems.length;i < length; i++)
        {
            todoListItems[i].completed = toggle.checked;
        }
        saveList();
        redrawList();
    }

    function undocheckboxHandler(event)
    {
        var index = 0, length = 0;
        var toggle = event.target;
        for(i=0, length =todoListItems.length;i < length; i++)
        {
            todoListItems[i].completed = toggle.checked;
        }
        saveList();
        redrawList();
    }

    function redrawList()
    {
        console.log("redraw");
        var incomplete= 0;
        var i;
        var list = document.getElementById('todo-list');
        var len = todoListItems.length;

        var filter = "all";

        //if(location.hash !==  '' && location.hash.match(/^#(all|completed|active)$/))
        //{
        //filter = location.hash.substring(1);
        //    filter = location.hash;
        //    console.log(location.hash);
        // }

        list.innerHTML = "";

        for (i = 0; i < len; i++)
        {
            var todo = todoListItems[i];
            console.log(todo);

            //if(!todo.completed)
            //{
            //    incomplete++;
            //}
            //if(filter == 'completed' && !todo.completed)
            //  continue;
            //if(filter == 'active' && todo.completed)
            //  continue;

            var item = document.createElement("li");
            item.id = "li_" + todo.id;
            if (todo.completed)
            {
                item.className += "completed";
            }
            //item.appendChild(document.createTextNode(todoListItems[i]));
            //list.appendChild(item);
            var checkbox = document.createElement('input');
            checkbox.className = "toggle";
            checkbox.type = "checkbox";
            checkbox.addEventListener('change', checkboxChangeHandler);
            checkbox.checked = todo.completed;
            checkbox.setAttribute('data-todo-id', todo.id);

            var label = document.createElement('label');
            label.appendChild(document.createTextNode(todo.title));
            label.addEventListener('dblclick', editItemHandler);
            label.setAttribute('data-todo-id', todo.id);

            var deleteButton = document.createElement('button');
            deleteButton.className = 'destroy';
            deleteButton.setAttribute('data-todo-id', todo.id);
            deleteButton.addEventListener('click', deleteClickHandler);


            var divDisplay = document.createElement('div');
            divDisplay.className = "view";
            divDisplay.appendChild(checkbox);
            divDisplay.appendChild(label);
            divDisplay.appendChild(deleteButton);
            item.appendChild(divDisplay);
            list.appendChild(item);
        }

        document.getElementById('toggle-all').checked = 0;

        var footer = document.getElementById('footer');
        footer.innerHTML = "";
        var todoCount = document.createElement('span');
        todoCount.id = "todo-count";
        var count = document.createElement('strong');
        count.appendChild(document.createTextNode(incomplete));
        todoCount.appendChild(count);
        var items = (incomplete == 1)? 'item' : 'items';
        todoCount.appendChild(document.createTextNode(" " + items + " left"  ));
        footer.appendChild(todoCount);

        if(len > 0 && (len - incomplete) > 0)
        {
            var filterList = document.createElement('ul');
            filterList.id = "filters";

            var allFilter = document.createElement("li");
            var allFilterLink = document.createElement("a");
            allFilterLink.href = "#all";
            allFilterLink.appendChild(document.createTextNode("All"));
            if(filter == 'All')
            {
                allFilterLink.className = "selected";
            }
            allFilter.appendChild(allFilterLink);
            filterList.appendChild(allFilter);

            var activeFilter = document.createElement("li");
            var activeFilterLink = document.createElement("a");
            activeFilterLink.appendChild(document.createTextNode('Active'));
            activeFilterLink.href = "#active";
            if(filter == 'active')
            {
                allFilterLink.className = "selected";
            }

            activeFilter.appendChild(activeFilterLink);
            filterList.appendChild(activeFilter);

            var completedFilter = document.createElement("li");
            var completedFilterLink = document.createElement('a');
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
            var button = document.createElement('button');
            button.id = 'clear-completed';
            button.appendChild(document.createTextNode("Clear completed (" + (len - incomplete) + ")"));
            button.addEventListener('click', removeAllCompletedHandler, false);
            footer.appendChild(button);
        }

        var undo_button = document.createElement('button');
        undo_button.id = 'undo-all';
        undo_button.setAttribute('data-todo-id', 0);
        undo_button.appendChild(document.createTextNode("Undo All(" + (len - incomplete) + ")"));
        undo_button.addEventListener('click', undocheckboxHandler, false);
        footer.appendChild(undo_button);
    }

    function reloadList(item) {
        console.log("reload");
        var stored = localStorage.getItem('todo-list');
        if (stored) {
            todoListItems = JSON.parse(stored);
            console.log(todoListItems);
            migrateData();
        }
        redrawList();
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
