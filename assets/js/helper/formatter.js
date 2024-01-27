

function formatMoney(money) {
  return (new Intl.NumberFormat("fr")).format(money);
}

export {formatMoney};