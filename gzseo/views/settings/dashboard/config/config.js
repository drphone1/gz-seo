jQuery(document).ready(function($) {
// Tabl Handler
    jQuery('.tab_link').click(function (e) {
        e.preventDefault();

        var activetab =  jQuery(this).attr("tab");
        if(activetab === undefined) return 0 ;
        let show = '#tab_'+activetab+'_manager';
        jQuery('.tab').removeClass('tab-active');
        jQuery(show).addClass('tab-active');

        jQuery('.tab_link').removeClass('active');
        jQuery(this).addClass('active');
    });


    const cards = document.querySelectorAll('.card');
    const headerColors = document.querySelector('.header-colors');
    const oddColors = document.querySelector('.odd-colors');
    const evenColors = document.querySelector('.even-colors');
    const element = document.getElementById("tablecolor_info");
    const table = document.getElementById("myTable");
    let selectedCard = null;

    cards.forEach(card => {
        card.addEventListener('click', () => {

            const tableColor = card.getAttribute('tablecolor').split(',');

            let title_back_header = document.querySelector('#title_back_header');
            title_back_header.value = tableColor[0];
            let title_word_header = document.querySelector('#title_word_header');
            title_word_header.value = tableColor[1];

            let title_back_odd = document.querySelector('#title_back_odd');
            title_back_odd.value = tableColor[2];
            let title_word_odd = document.querySelector('#title_word_odd');
            title_word_odd.value = tableColor[3];

            let title_back_even = document.querySelector('#title_back_even');
            title_back_even.value = tableColor[4];
            let title_word_even = document.querySelector('#title_word_even');
            title_word_even.value = tableColor[5];


            if (element) {
                element.style.display = 'block';
            } else {
                console.error('Element with ID "tablecolor_info" not found.');
            }
            if (selectedCard) {
                selectedCard.style.boxShadow = '';
                selectedCard.style.transform = '';
            }
            selectedCard = card;
            const cards = document.getElementsByClassName('card');
            jQuery(".card").removeClass("card-active");
            selectedCard.classList.add("card-active");
            updateTableColors();
        });
    })

    function updateTableColors() {
        const headerColor = document.querySelector('#title_back_header').value;
        const headerTextColor = document.querySelector('#title_word_header').value;
        const oddRowColor = document.querySelector('#title_back_odd').value;
        const oddTextColor = document.querySelector('#title_word_odd').value;
        const evenRowColor = document.querySelector('#title_back_even').value;
        const evenTextColor = document.querySelector('#title_word_even').value;

        const headerCells = table.querySelectorAll('th');
        headerCells.forEach(cell => {
            cell.style.backgroundColor = headerColor;
            cell.style.color = headerTextColor;
        });

        const rows = table.querySelectorAll('tbody tr');
        rows.forEach((row, index) => {
            if (index % 2 === 0) { // ردیف زوج

                row.style.backgroundColor = oddRowColor;
                row.style.color = oddTextColor;
            } else { // ردیف فرد
                row.style.backgroundColor = evenRowColor;
                row.style.color = evenTextColor;
            }
        });
    }

    document.getElementById('title_back_header').addEventListener('input', function() {
        document.querySelector('table thead tr').style.backgroundColor = this.value;
        document.querySelectorAll('table thead th').forEach(th => {
            th.style.backgroundColor = this.value;
        });
    });
    document.getElementById('title_word_header').addEventListener('input', function() {
        document.querySelectorAll('table thead th').forEach(th => {
            th.style.color = this.value;
        });
    });
    document.getElementById('title_back_even').addEventListener('input', function() {
        var rows = document.querySelectorAll('table tbody tr');
        for (var i = 0; i < rows.length; i++) {
            if (i % 2 !== 0) {
                rows[i].style.backgroundColor = this.value;
            }
        }
    });
    document.getElementById('title_word_even').addEventListener('input', function() {
        var rows = document.querySelectorAll('table tbody tr');
        for (var i = 0; i < rows.length; i++) {
            if (i % 2 !== 0) {
                rows[i].style.color = this.value;
            }
        }
    });
    document.getElementById('title_back_odd').addEventListener('input', function() {
        var rows = document.querySelectorAll('table tbody tr');
        for (var i = 0; i < rows.length; i++) {
            if (i % 2 === 0) {
                rows[i].style.backgroundColor = this.value;
            }
        }
    });
    document.getElementById('title_word_odd').addEventListener('input', function() {
        var rows = document.querySelectorAll('table tbody tr');
        for (var i = 0; i < rows.length; i++) {
            if (i % 2 === 0) {
                rows[i].style.color = this.value;
            }
        }
    });

    document.getElementById('gzseo_submit_table_style').addEventListener('click', function() {
        title_back_header = document.getElementById('title_back_header').value
        title_word_header = document.getElementById('title_word_header').value
        title_back_odd = document.getElementById('title_back_odd').value
        title_word_odd = document.getElementById('title_word_odd').value
        title_back_even = document.getElementById('title_back_even').value
        title_word_even = document.getElementById('title_word_even').value

        const data1 = {
            color : title_back_header + ","+ title_word_header +","+  title_back_odd  +","+  title_word_odd  +","+   title_back_even +","+ title_word_even,
            use_defult_style : document.getElementById('gzseo_table_style_checkbox').checked,
            table_position: $('#table_positions_select').find(":selected").val(),
        }

        const nonce = document.getElementById("gzseo_submit_table_style_nonce").value;

        const data = {
            action: 'gzseo_submit_table_style',
            data: data1,
            nonce:nonce

        };

        jQuery.post(ajaxurl, data, function(response) {
            if (response.success) {
                console.log(response);
                alert("با موفقیت ارسال شد");
            } else {
                alert("ارسال ناموفق بود.");
            }
        })

    });


    document.getElementById('save_setings').addEventListener('click', function() {
        const nonce = document.getElementById("gzseo_setings_nonce").value;
        setings={
            Tone_of_Content : document.getElementById('Tone_of_Content').value,
            Word_Count : document.getElementById('Word_Count').value,
        }
        // Ajax request to save setings comments
        const data = {
            action: 'gzseo_config',
            setings: setings,
            nonce:nonce
        };

        jQuery.post(ajaxurl, data, function(response) {
            if (response.success) {
                alert("تنظیمات ذخیره شد ");
            } else {
                alert("خطا در ذخیره");
            }
        });


    });

});