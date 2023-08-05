export const state = () => ({
  products: {
    units: [
      { value: 'kg', text: "kg" },
      { value: 'ks', text: "ks" },
    ],
  },
  movements: {
    types: [
      { value: 'move', text: 'Převodka' },
      { value: 'receive', text: 'Příjemka' },
    ]
  }
});

export const getters = {
  // Customers -------------------------------------------------------
  getProductsUnits: (state) => {
    return state.products.units;
  },
  getMovementTypes: state => {
    return state.movements.types;
  }
};

export const mutations = {};

export const actions = {};
