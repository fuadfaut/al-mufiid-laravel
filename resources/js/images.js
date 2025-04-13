// This file is used to import images so they can be processed by Vite
// Import all images from the public/images directory
const publicImages = import.meta.glob([
  '../../public/images/**/*.png',
  '../../public/images/**/*.jpg',
  '../../public/images/**/*.jpeg',
  '../../public/images/**/*.svg',
  '../../public/images/**/*.gif',
]);

// Import all images from the resources/images directory
const resourceImages = import.meta.glob([
  '../images/**/*.png',
  '../images/**/*.jpg',
  '../images/**/*.jpeg',
  '../images/**/*.svg',
  '../images/**/*.gif',
]);

export { publicImages, resourceImages };
