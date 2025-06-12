const Utils = {
  maskCpf: (cpf) => {
    if (!cpf) return ''

    let value = cpf.replace(/\D/g, '')

    value = value.slice(0, 11)

    value = value.replace(/(\d{3})(\d)/, '$1.$2')
    value = value.replace(/(\d{3})(\d)/, '$1.$2')
    value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2')

    return value
  },

  maskCnpj: (cnpj) => {
    if (!cnpj) return ''

    let value = cnpj.replace(/\D/g, '')

    value = value.slice(0, 14)

    value = value.replace(/^(\d{2})(\d)/, '$1.$2')
    value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
    value = value.replace(/\.(\d{3})(\d)/, '.$1/$2')
    value = value.replace(/(\d{4})(\d)/, '$1-$2')

    return value
  }
}

export default Utils
