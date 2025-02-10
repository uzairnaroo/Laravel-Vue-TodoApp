# Use official Node.js image
FROM node:16

# Set working directory
WORKDIR /app

# Copy package.json and package-lock.json
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy the rest of the application
COPY . .

# Ensure the 'node' user owns the application files
RUN chown -R node:node /app

# Switch to the 'node' user provided by the base image
USER node

# Set environment variable for the port
ENV PORT=8085

# Expose port 8085
EXPOSE 8085

# Start the application
CMD ["npm", "run", "serve"]
