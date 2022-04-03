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

function normalizeAmount(amount) {
    amount = amount.replace(',','.');
    return parseFloat(amount);
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
    $('input[name*=price]').on( 'change', calculateTotalAmounts );
    $('input[name*=count]').on( 'change', calculateTotalAmounts );
}

function removeProduct() {
    $(this).parent().parent().remove();
    calculateTotalAmounts();
    return false;
}