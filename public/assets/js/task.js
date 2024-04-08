(function () {






    /******************************************************************************************
     *
     *   finish ()
     *  Arg: array input
     *   Desc: replace the current todo array after successful promise and redraw the list
     */
    function finish(array)
    {
        todoListItems = array;
        redrawList();
    }

    /******************************************************************************************
     *
     *   fin()
     *  Arg: error message
     *   Desc: function to handle post failed promise
     */
    function fin(error)
    {
        //console.log(error);
    }

    /******************************************************************************************
     *
     *   reloadList ()
     *  Desc: gets the array of tasks from backend and utilizes a promise to wait for the data.
     */
    function reloadList() {
        let stored;

        let myPromise = new Promise(function(myResolve, myReject) {
            $.get("task/getlist").done(function(data){
                stored = data.data;
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

    /******************************************************************************************
     *
     *   toggleAllHandler ()
     *
     *   desc: handle function to check off all the tasks in the list
     */
    function toggleAllHandler(event)
    {
        let index = 0, length = 0;
        let toggle = event.target;
        for(i=0, length =todoListItems.length;i < length; i++)
        {
            todoListItems[i].completed = toggle.checked;
        }
        redrawList();
    }

    /******************************************************************************************
     *
     *   newTodoKeyPressHandler ()
     *  arg:event object
     *   desc: handler function when a new task in entered
     */
    function newTodoKeyPressHandler(event) {
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
        document.getElementById('toggle-all').addEventListener('change', toggleAllHandler, false);
        document.getElementById('new-todo').addEventListener('keypress', newTodoKeyPressHandler, false);
    }
}());
