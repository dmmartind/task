(function () {


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
