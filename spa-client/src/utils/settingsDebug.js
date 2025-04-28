import axios from 'axios';

/**
 * Fetch and display all settings from the database
 * This is a debug utility function
 */
export async function fetchAllSettings() {
  try {
    const response = await axios.get('http://localhost:8000/api/settings');
    console.log('All settings from database:', response.data);
    return response.data;
  } catch (error) {
    console.error('Failed to fetch settings:', error);
    return null;
  }
}

/**
 * Fetch a specific setting by ID
 */
export async function fetchSettingById(id) {
  try {
    const response = await axios.get(`http://localhost:8000/api/settings/${id}`);
    console.log(`Setting with ID ${id}:`, response.data);
    return response.data;
  } catch (error) {
    console.error(`Failed to fetch setting with ID ${id}:`, error);
    return null;
  }
}

/**
 * Test all possible endpoints for the message setting
 */
export async function testMessageEndpoints() {
  const endpoints = [
    'http://localhost:8000/api/settings/14',
    'http://localhost:8000/api/settings/message',
    'http://localhost:8000/api/settings?key=message',
    'http://localhost:8000/api/settings/by-key/message'
  ];
  
  console.log('Testing all possible message endpoints:');
  
  for (const endpoint of endpoints) {
    try {
      const response = await axios.get(endpoint);
      console.log(`Response from ${endpoint}:`, response.data);
    } catch (error) {
      console.error(`Failed to fetch from ${endpoint}:`, error.message);
    }
  }
} 