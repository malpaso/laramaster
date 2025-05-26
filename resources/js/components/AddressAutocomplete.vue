<script setup>
import { ref, nextTick } from 'vue'
import { router } from '@inertiajs/vue3'

// Define emits
const emit = defineEmits(['address-selected', 'address-cleared'])

// Define props (optional - for external form integration)
const props = defineProps({
  form: {
    type: Object,
    default: null
  },
  fieldName: {
    type: String,
    default: 'address'
  }
})

// Reactive data
const query = ref('')
const suggestions = ref([])
const selectedAddress = ref(null)
const showDropdown = ref(false)
const loading = ref(false)
const highlightedIndex = ref(-1)

// Debounce timeout
let debounceTimeout = null

// Mock address data - replace with actual API call
const mockAddresses = [
  { id: 1, street: '123 Collins Street', city: 'Melbourne', state: 'VIC', postCode: '3000' },
  { id: 2, street: '456 George Street', city: 'Sydney', state: 'NSW', postCode: '2000' },
  { id: 3, street: '789 Queen Street', city: 'Brisbane', state: 'QLD', postCode: '4000' },
  { id: 4, street: '321 King William Street', city: 'Adelaide', state: 'SA', postCode: '5000' },
  { id: 5, street: '654 St Georges Terrace', city: 'Perth', state: 'WA', postCode: '6000' },
  { id: 6, street: '987 Elizabeth Street', city: 'Hobart', state: 'TAS', postCode: '7000' },
  { id: 7, street: '147 Smith Street', city: 'Darwin', state: 'NT', postCode: '0800' },
  { id: 8, street: '258 London Circuit', city: 'Canberra', state: 'ACT', postCode: '2600' },
  { id: 9, street: '369 Chapel Street', city: 'South Yarra', state: 'VIC', postCode: '3141' },
  { id: 10, street: '741 Toorak Road', city: 'Toorak', state: 'VIC', postCode: '3142' },
  { id: 11, street: '852 Brunswick Street', city: 'Fitzroy', state: 'VIC', postCode: '3065' },
  { id: 12, street: '963 Swan Street', city: 'Richmond', state: 'VIC', postCode: '3121' },
  { id: 13, street: '159 Oxford Street', city: 'Bondi Junction', state: 'NSW', postCode: '2022' },
  { id: 14, street: '357 Crown Street', city: 'Surry Hills', state: 'NSW', postCode: '2010' },
  { id: 15, street: '468 Military Road', city: 'Mosman', state: 'NSW', postCode: '2088' },
  { id: 16, street: '579 The Corso', city: 'Manly', state: 'NSW', postCode: '2095' },
  { id: 17, street: '680 Given Terrace', city: 'Paddington', state: 'QLD', postCode: '4064' },
  { id: 18, street: '791 Melbourne Street', city: 'South Brisbane', state: 'QLD', postCode: '4101' },
  { id: 19, street: '234 Adelaide Street', city: 'Brisbane', state: 'QLD', postCode: '4000' },
  { id: 20, street: '345 Boundary Street', city: 'West End', state: 'QLD', postCode: '4101' },
  { id: 21, street: '456 Rundle Street', city: 'Adelaide', state: 'SA', postCode: '5000' },
  { id: 22, street: '567 Hindley Street', city: 'Adelaide', state: 'SA', postCode: '5000' },
  { id: 23, street: '678 Unley Road', city: 'Unley', state: 'SA', postCode: '5061' },
  { id: 24, street: '789 Hay Street', city: 'Perth', state: 'WA', postCode: '6000' },
  { id: 25, street: '890 Beaufort Street', city: 'Mount Lawley', state: 'WA', postCode: '6050' },
  { id: 26, street: '901 Canning Highway', city: 'South Perth', state: 'WA', postCode: '6151' },
  { id: 27, street: '123 Salamanca Place', city: 'Hobart', state: 'TAS', postCode: '7000' },
  { id: 28, street: '234 Macquarie Street', city: 'Hobart', state: 'TAS', postCode: '7000' },
  { id: 29, street: '345 Mitchell Street', city: 'Darwin', state: 'NT', postCode: '0800' },
  { id: 30, street: '456 Commonwealth Avenue', city: 'Canberra', state: 'ACT', postCode: '2600' }
]

// Mock API call function
const searchAddresses = async (searchQuery) => {
  // Simulate API delay
  await new Promise(resolve => setTimeout(resolve, 300))

  // Filter mock data based on query
  return mockAddresses.filter(address =>
    address.street.toLowerCase().includes(searchQuery.toLowerCase()) ||
    address.city.toLowerCase().includes(searchQuery.toLowerCase()) ||
    address.state.toLowerCase().includes(searchQuery.toLowerCase()) ||
    address.postCode.includes(searchQuery)
  ).slice(0, 5) // Limit to 5 results
}

// Handle input changes with debouncing
const handleInput = () => {
  clearTimeout(debounceTimeout)
  highlightedIndex.value = -1

  if (query.value.length < 2) {
    suggestions.value = []
    showDropdown.value = false
    return
  }

  debounceTimeout = setTimeout(async () => {
    loading.value = true
    try {
      suggestions.value = await searchAddresses(query.value)
      showDropdown.value = true
    } catch (error) {
      console.error('Address search failed:', error)
      suggestions.value = []
    } finally {
      loading.value = false
    }
  }, 300)
}

// Handle input blur
const handleBlur = () => {
  // Delay hiding dropdown to allow for click events
  setTimeout(() => {
    showDropdown.value = false
    highlightedIndex.value = -1
  }, 200)
}

// Handle keyboard navigation
const handleKeydown = (event) => {
  if (!showDropdown.value || suggestions.value.length === 0) return

  switch (event.key) {
    case 'ArrowDown':
      event.preventDefault()
      highlightedIndex.value = Math.min(highlightedIndex.value + 1, suggestions.value.length - 1)
      break
    case 'ArrowUp':
      event.preventDefault()
      highlightedIndex.value = Math.max(highlightedIndex.value - 1, -1)
      break
    case 'Enter':
      event.preventDefault()
      if (highlightedIndex.value >= 0) {
        selectAddress(suggestions.value[highlightedIndex.value])
      }
      break
    case 'Escape':
      showDropdown.value = false
      highlightedIndex.value = -1
      break
  }
}

// Select an address
const selectAddress = (address) => {
  selectedAddress.value = address
  query.value = `${address.street}, ${address.city}, ${address.state} ${address.postCode}`
  suggestions.value = []
  showDropdown.value = false
  highlightedIndex.value = -1

  // Emit event for parent component
  emit('address-selected', address)

  // Optional: Update external form object directly
  if (props.form && props.fieldName) {
    props.form[props.fieldName] = address
  }
}

// Clear the selected address
const clearSelection = () => {
  selectedAddress.value = null
  query.value = ''
  suggestions.value = []
  showDropdown.value = false

  // Emit event for parent component
  emit('address-cleared')

  // Optional: Clear external form object
  if (props.form && props.fieldName) {
    props.form[props.fieldName] = null
  }
}
</script>

<template>
  <div class="relative w-full max-w-md">

    <div class="relative">
      <input
        id="address"
        v-model="query"
        @input="handleInput"
        @focus="showDropdown = true"
        @blur="handleBlur"
        @keydown="handleKeydown"
        type="text"
        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        placeholder="Start typing an address..."
        autocomplete="off"
      />

      <!-- Loading indicator -->
      <div
        v-if="loading"
        class="absolute right-3 top-1/2 transform -translate-y-1/2"
      >
        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-500"></div>
      </div>
    </div>

    <!-- Dropdown with suggestions -->
    <div
      v-if="showDropdown && suggestions.length > 0"
      class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto"
    >
      <ul class="py-1">
        <li
          v-for="(suggestion, index) in suggestions"
          :key="suggestion.id"
          @mousedown="selectAddress(suggestion)"
          @mouseenter="highlightedIndex = index"
          :class="[
            'px-3 py-2 cursor-pointer text-sm',
            highlightedIndex === index ? 'bg-blue-50 text-blue-900' : 'text-gray-900 hover:bg-gray-50'
          ]"
        >
          <div class="font-medium">{{ suggestion.street }}</div>
          <div class="text-gray-500 text-xs">
            {{ suggestion.city }}, {{ suggestion.state }} {{ suggestion.postCode }}
          </div>
        </li>
      </ul>
    </div>

    <!-- No results message -->
    <div
      v-if="showDropdown && query.length >= 2 && suggestions.length === 0 && !loading"
      class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg"
    >
      <div class="px-3 py-2 text-sm text-gray-500">
        No addresses found
      </div>
    </div>

    <!-- Selected address display -->
    <div v-if="selectedAddress" class="mt-3 p-3 bg-gray-50 rounded-md">
      <div class="text-sm font-medium text-gray-900">Selected Address:</div>
      <div class="text-sm text-gray-700">
        {{ selectedAddress.street }}<br>
        {{ selectedAddress.city }}, {{ selectedAddress.state }} {{ selectedAddress.postCode }}
      </div>
      <button
        @click="clearSelection"
        class="mt-2 text-xs text-blue-600 hover:text-blue-800"
      >
        Clear selection
      </button>
    </div>
  </div>
</template>

<style scoped>
/* Additional custom styles if needed */
</style>
