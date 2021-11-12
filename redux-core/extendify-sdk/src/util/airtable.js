export function createTemplatesFilterFormula(filters) {
    const { taxonomies, search, type } = filters
    const formula = []

    // Builds the taxonomy list by looping over all supplied taxonomies
    const taxFormula = Object.entries(taxonomies)
        .filter((tax) => Boolean(tax[1].length))
        .map((tax) => `${tax[0]} = "${tax[1]}"`)
        .join(', ')

    taxFormula.length && formula.push(taxFormula)
    search?.length && formula.push(`OR(FIND(LOWER("${search}"), LOWER(title))!= 0, FIND(LOWER("${search}"), LOWER({tax_categories})) != 0)`)
    type.length && formula.push(`{type}="${type}"`)

    return formula.length
        ? `AND(${formula.join(', ')})`.replace(/\r?\n|\r/g, '')
        : ''
}
