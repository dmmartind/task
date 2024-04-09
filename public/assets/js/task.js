(function () {


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
