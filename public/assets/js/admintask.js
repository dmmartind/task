(function () {
    let todoListItems = [];


    function redrawList()
    {
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
            item.id = "li_" + todo.guid;
            todo.completed = todo.completed;
            if (todo.completed)
            {
                item.className += "completed";
            }

            let checkbox = document.createElement('input');
            checkbox.className = "toggle";
            checkbox.type = "checkbox";
            //checkbox.addEventListener('change', checkboxChangeHandler);
            checkbox.checked = todo.completed;
            checkbox.setAttribute('data-todo-id', todo.guid);

            let plabel = document.createElement('label');
            plabel.appendChild(document.createTextNode(todo.title));
            plabel.innerHTML="priority";
            plabel.className = "priorityLabel";
            //plabel.addEventListener('dblclick', editItemHandler);
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
            //label.addEventListener('dblclick', editItemHandler);
            label.setAttribute('data-todo-id', todo.guid);

            let deleteButton = document.createElement('button');
            deleteButton.className = 'destroy';
            deleteButton.setAttribute('data-todo-id', todo.guid);
            //deleteButton.addEventListener('click', deleteClickHandler);


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
            //button.addEventListener('click', removeAllCompletedHandler, false);
            footer.appendChild(button);
        }

        let undo_button = document.createElement('button');
        undo_button.id = 'undo-all';
        undo_button.setAttribute('data-todo-id', 0);
        undo_button.appendChild(document.createTextNode("Undo All(" + (len - incomplete) + ")"));
        //undo_button.addEventListener('click', undocheckboxHandler, false);
        footer.appendChild(undo_button);
    }


    function finish(array)
    {
        todoListItems = array;
        redrawList();
    }


    function fin(error)
    {
        console.log(error);
    }

    function reloadList() {
        let stored;
        const queryString = window.location.search;
        let result = queryString.replace('?','&');
        let myPromise = new Promise(function(myResolve, myReject) {
            $.get("ajax.inc.php?action=admin_getlist" + result).done(function(data){
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
        reloadList();
    }
}());
