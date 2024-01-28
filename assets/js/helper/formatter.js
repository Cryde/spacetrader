function formatMoney(money) {
  return (new Intl.NumberFormat("fr")).format(money);
}

function formatPercent(percent) {
  return new Intl.NumberFormat('fr', {style: 'percent', minimumFractionDigits: 2}).format(percent / 100);
}

export {formatMoney, formatPercent};