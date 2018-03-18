HTMLCollection.prototype.select = function(options)
{
    elements = this;

    for (var element of elements)
    {
        element.select(options);
    }
}

HTMLElement.prototype.select = function(options)
{
    element = this;

    let defaults = {
		transition: "slide-fade",
        staticWidth: false,
        afterMount: false,
        afterChange: false,
        afterOpen: false,
        afterClose: false,
        search: false,
        text: {
            search: "Поиск",
            notFound: "Нет результатов"
        }
	}; 
	
    var opts = Object.assign(defaults, options);

    var options = [],
        selected,
        opened = false,
        transition,
        _select = element,
        _component,
        _input,
        _value,
        _values,
        _search;

    function mount()
    {
        init();
        construct();
        bindActions();

        if (opts.afterMount)
            opts.afterMount();
    }

    function init()
    {
        indexOptions();

        if (selected == undefined && options.length)
            selected = options[0];
    }

    function indexOptions()
    {
        let el;

        Array.from(_select.children).forEach(function(element) {
            el = {
                value: element.value,
                text: element.innerHTML
            };

            options.push(el);

            if (element.selected)
                selected = el;
        }, this);
    }

    function construct()
    {
        let baseWidth = _select.offsetWidth;

        _select.setAttribute("custom-select", "true");

        _input = _select.cloneNode(true);

        // Input
        var base_classes = _input.getAttribute("class");
        _input.setAttribute("class", "select__input");

        // Selected value block
        _value = document.createElement("div");
        _value.setAttribute("class", "select__value");
        _value.innerText = selected.text;

        // ===============
        // Values
        _values = document.createElement("div");
        _values.setAttribute("class", "select__values");

        // Search
        if (opts.search == true) {
            let _searchContainer = document.createElement("div");
            _searchContainer.setAttribute("class", "select__search__container");
            _search = document.createElement("input");
            _search.setAttribute("class", "select__search form__group__input");
            _search.setAttribute("placeholder", opts.text.search);

            _search.addEventListener("keyup", function(event){
                searchFindInList(_search.value);
            });

            _searchContainer.appendChild(_search);

            _values.appendChild(_searchContainer);
        }

        let el;
        options.forEach(function(element, index) {
            el = document.createElement("div");
            el.setAttribute("class", "select__values__option");
            el.innerText = element.text;
            el.dataset.index = index;

            if (element.value == selected.value)
                el.classList.add("select__values__option--active");

            _values.appendChild(el);
        }, this);
        // ===============

        // Whole component
        _component = document.createElement("div");
        _component.setAttribute("class", base_classes);

        _component.appendChild(_input);
        _component.appendChild(_value);
        _component.appendChild(_values);
    
        // Replacement
        _select.replaceWith(_component);
        _input.style.display = 'none';

        // Styles
        if (opts.staticWidth) {
            _value.style.width = baseWidth + "px";
            _values.style.width = baseWidth + "px";
        }

        // Creating transition
        transition = new Transition(_values, opts.transition);
    }

    function bindActions()
    {
        Array.from(_values.children).forEach(function(element) {
            if (element.classList.contains('select__values__option'))
                element.addEventListener("click", function(event) {
                    onElementClick(event, element);
                });
        });

        _input.addEventListener("change", onChange);
        
        _value.addEventListener("click", triggerValues);

        document.addEventListener("click", function(event) {
            var isClickInside = _component.contains(event.target);

            if (!isClickInside)
                hideValues();
        });
    }

    function select(index, trigger = false) {
        selected = options[index];
        
        if (trigger)
            triggerEvent(_input, "change");
    }

    function selectByValue(value) {
        for (let i in options) {
            if (options[i].value == value)
                selected = options[i];
        }
    }

    function triggerEvent(element, event) {
        element.dispatchEvent(new Event(event));
    }



    function updateValuesState() {
        if (opened) {
            _value.classList.add("select__value--active");
            _values.classList.add("select__values--visible");

            transition.enter();
        } else {
            _value.classList.remove("select__value--active");
            _values.classList.remove("select__values--visible");

            transition.leave();
        }
    }
    
    function triggerValues() {
        opened = !opened;
        updateValuesState();

        if (opened) {
            if (opts.search !== false)
                _search.focus();
        }
    }

    function hideValues() {
        opened = false;
        updateValuesState();

        if (opts.afterClose)
            opts.afterClose();
    }

    function showValues() {
        opened = true;
        updateValuesState();

        if (opts.search !== false)
            _search.focus();

        if (opts.afterOpen)
            opts.afterOpen();
    }

    function onChange(event) {
        _value.innerText = selected.text;
        _input.value = selected.value;

        if (opts.afterChange)
            opts.afterChange(selected);
    }

    function onElementClick(event, element) {
        select(element.dataset.index, true);
        
        Array.from(_values.childNodes).forEach(function(el){
            el.classList.remove("select__values__option--active");
        });

        element.classList.add("select__values__option--active");

        hideValues();
    }

    function searchFindInList(value) {
        var input, filter, ul, li, i, el;
        input = _search;
        filter = input.value.toUpperCase();
        ul = _values;
        li = _values.getElementsByClassName("select__values__option");

        var hidden = 0;
        var visible = 0;
        
        for (i = 0; i < li.length; i++) { 
            el = li[i];

            if (el.innerHTML.toUpperCase().indexOf(filter) > -1) {
                el.style.display = "";
                visible++;
            } else {
                el.style.display = "none";
                hidden++;
            }
        }

        _notFound = document.createElement("div");
        _notFound.setAttribute("class", "select__search__not-found");
        _notFound.innerHTML = opts.text.notFound;

        if (hidden == li.length)
            if (_values.getElementsByClassName("select__search__not-found").length == 0)
                _values.appendChild(_notFound);
        
        if (visible > 0)
            if (_values.getElementsByClassName("select__search__not-found").length > 0)
                _values.getElementsByClassName("select__search__not-found")[0].remove();
    }
    
    if (_select.getAttribute("custom-select") != "true")
        mount();
}