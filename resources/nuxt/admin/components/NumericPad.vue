<template>
  <div class="row numeric-pad">
    <div class="col">
      <div class="pad" @click="writeDown('1')">1</div>
      <div class="pad" @click="writeDown('4')">4</div>
      <div class="pad" @click="writeDown('7')">7</div>
      <div class="pad text-warning" @click="removeLast()">
        &nbsp;
        <svg xmlns="http://www.w3.org/2000/svg" style="width: 32px; height: 32px;" fill="currentColor" class="bi bi-backspace" viewBox="0 0 16 16">
          <path d="M5.83 5.146a.5.5 0 0 0 0 .708L7.975 8l-2.147 2.146a.5.5 0 0 0 .707.708l2.147-2.147 2.146 2.147a.5.5 0 0 0 .707-.708L9.39 8l2.146-2.146a.5.5 0 0 0-.707-.708L8.683 7.293 6.536 5.146a.5.5 0 0 0-.707 0z"/>
          <path d="M13.683 1a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-7.08a2 2 0 0 1-1.519-.698L.241 8.65a1 1 0 0 1 0-1.302L5.084 1.7A2 2 0 0 1 6.603 1h7.08zm-7.08 1a1 1 0 0 0-.76.35L1 8l4.844 5.65a1 1 0 0 0 .759.35h7.08a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1h-7.08z"/>
        </svg>
        &nbsp;
      </div>
      <div class="pad text-danger" @click="deleteValue()">C</div>
    </div>
    <div class="col">
      <div class="pad" @click="writeDown('2')">2</div>
      <div class="pad" @click="writeDown('5')">5</div>
      <div class="pad" @click="writeDown('8')">8</div>
      <div class="pad" @click="writeDown('0')">0</div>
    </div>
    <div class="col">
      <div class="pad" @click="writeDown('3')">3</div>
      <div class="pad" @click="writeDown('6')">6</div>
      <div class="pad" @click="writeDown('9')">9</div>
      <div class="pad" @click="writeDown('.')">.</div>
      <div class="pad text-success" :class="{'disabled': loading}" @click="submitForm()">
        <div v-if="loading" class="spinner-border text-primary" role="status"></div>

        <svg v-else xmlns="http://www.w3.org/2000/svg" style="width: 32px; height: 32px;" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
          <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
        </svg>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "NumericPad",
  props: {
    loading: {
      type: Boolean,
      default: false,
    }
  },
  data() {
    return {
      currentInput: 'amount',
    }
  },
  mounted() {
    this.setCurrentInput('amount');
  },
  methods: {
    writeDown(number) {
      let currentInput = this.getCurrentInput();
      let input = document.getElementById(currentInput);

      if (
        !input.value.includes('.') &&
        parseFloat(input.value) === 0 &&
        number !== '.'
      ) {} else {
        number = input.value + number;
      }

      this.$emit('change', {type: this.currentInput, value: number})
    },
    removeLast() {
      let currentInput = this.getCurrentInput();
      let input = document.getElementById(currentInput);

      if (input.value.length > 1) {
        let removeChars = 1;
        if (input.value[input.value.length - 2] === '.') {
          removeChars = 2;
        }

        this.$emit('change', {type: this.currentInput, value: input.value.substr(0, input.value.length - removeChars)})
      } else {
        this.$emit('change', {type: this.currentInput, value: 0})
      }
    },
    deleteValue() {
      // let currentInput = this.getCurrentInput();
      // let input = document.getElementById(currentInput);

      this.$emit('change', {type: this.currentInput, value: 0})
    },
    submitForm() {
      if (this.loading) {
        return;
      }

      this.$emit('submit');
    },


    setCurrentInput(value) {
      this.currentInput = value;

      let currentInput = this.getCurrentInput();

      if (currentInput && document.getElementById(currentInput)) {
        document.getElementById(currentInput).classList.remove('border-success');
      }

      window.localStorage.setItem('currentInput', value);

      let el = document.getElementById(value);
      el.classList.add('border-success');

      // if (el.value === '0.00') {
      this.$emit('change', {type: this.currentInput, value: 0})

      // }x
    },

    getCurrentInput() {
      return window.localStorage.getItem('currentInput');
    },
  },
}
</script>

<style scoped>

</style>
