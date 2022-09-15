const listCountries = $.masksSort($.masksLoad('http://crm.vamgazeta.ru/assets/js/phones.json'), ['#'], /[0-9]|#/, "mask");
const maskOpts = {
    inputmask: {
        definitions: {
            '#': {
                validator: "[0-9]",
                cardinality: 1
            }
        },
        showMaskOnHover: false,
        autoUnmask: true,
        clearMaskOnLostFocus: false
    },
    match: /[0-9]/,
    replace: '#',
    listKey: "mask"
};

const maskChangeWorld = function(maskObj, determined) {
    if (determined) {
        if (maskObj.type != "mobile") {
            $("#descr").html(maskObj.city.toString() + " (" + maskObj.region.toString() + ")");
        } else {
            $("#descr").html("мобильный телефон");
        }
    } else {
        $("#descr").html("Введите телефон");
    }
}

$('input[name*="phone"]').inputmasks($.extend(true, {}, maskOpts, {
    list: listCountries,
    onMaskChange: maskChangeWorld
}));


$(document).ready(function() {
    const agentChosen = $("#orders_agent");
    if (!!agentChosen) {
        agentChosen.chosen();
    }
    const $collectionHolder = $('tbody.products');

    $collectionHolder.data('index', $collectionHolder.find('tr').length);

    $('body').on('click', '#addProduct', function(e) {
        addFormToCollection($collectionHolder);
    })

    calculateTotalAmounts();
});


function getDisabledDates(event) {
    const elemIdx = event.data.idx;
    let disabledDates = [];
    let disabledYears = [];
    let disabledMonth =[]

    let paper = $(this).find(":selected").text()

    $('.loader-block').toggleClass("hidden");
    fetch('//crm.vamgazeta.ru/api/archives/get-by-paper/' + encodeURIComponent(paper) + '?token=f72R2yQLZEc@rqF2vSB9pHa_iq')
        .then(resp => resp.json())
        .then(array => {
            $('.loader-block').toggleClass("hidden");
            if (!!array.dates) {
                for (let d = 0; d < array.dates.length; d++) {
                    disabledDates.push(array.dates[d].datePaper)
                    const year = new Date(formatDate(array.dates[d].datePaper)).getFullYear();
                    const month = [year, new Date(formatDate(array.dates[d].datePaper)).getMonth(), "01"].join("-")
                    if (disabledYears.indexOf(year) == -1) {
                        disabledYears.push(year)
                    }
                    if (disabledMonth.indexOf(month) == -1) {
                        disabledMonth.push(month)
                    }
                }
            }

            $(this).parent().parent().find('input[name*="[price]"]').val(array.price);

            const dataElem = $('input[name*="['+elemIdx+'][date_paper]"]');
            if (!!dataElem.data('datepicker')) {
                dataElem.data('datepicker').destroy();
            }
            dataElem.datepicker({
                dateFormat: 'yyyy-mm-dd',
                autoClose: true,
                onRenderCell: function (date, cellType) {
                    const year = new Date(formatDate(date)).getFullYear();
                    const month = [year, new Date(formatDate(date)).getMonth(), "01"].join("-");
                    const day = formatDate(date);

                    switch (cellType) {
                        case 'year':
                            return {
                                disabled: disabledYears.indexOf(year) == -1
                            }
                        case 'month':
                            return {
                                disabled: disabledMonth.indexOf(month) == -1
                            }
                        case 'day':
                            return {
                                disabled: disabledDates.indexOf(day) == -1
                            }
                    }

                },
                onSelect: function onSelect(title, date) {
                    console.log(formatDate(date))
                }
            });
        });
}

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2)
        month = '0' + month;
    if (day.length < 2)
        day = '0' + day;

    return [year, month, day].join('-');
}

function normalizeAmount(amount) {
    amount = amount.replace(',','.');
    return parseFloat(amount);
}

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

function calculateTotalAmounts() {
    let summa = 0;
    if (location.href.indexOf('orders/') < 0) {
        return;
    }
    $('tbody.products tr').each(function(){
        $this = $(this);

        const count = $this.find('input[name*=count]').val();
        const price = normalizeAmount($this.find('input[name*=price]').val());

        summa += count * price;
    });

    $('#itogo')[0].innerHTML = summa;
    $('#orders_summ')[0].value = summa;
}

function addFormToCollection($collectionHolder) {
    const prototype = $collectionHolder.data('prototype');

    const index = $collectionHolder.data('index');

    const newForm = prototype.replace(/__name__/g, index);

    $collectionHolder.data('index', index + 1);

    $('tbody.products').append(newForm);
    $('.removeProduct').on( 'click', removeProduct);
    $('input[name*="['+index+'][price]"]').on( 'change', calculateTotalAmounts );
    $('input[name*="['+index+'][count]"]').on( 'change', calculateTotalAmounts );

    const nomen = $('select[name*="['+index+'][nomenclature]"]');
    nomen.on('change', { idx: index },getDisabledDates);
    nomen.change();

    sleep(500).then(() => {
        calculateTotalAmounts();
    });
}

function removeProduct() {
    $(this).parent().parent().remove();
    calculateTotalAmounts();
    return false;
}