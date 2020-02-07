node {
        environment {
            registry = "acrncpltest.azurecr.io/konsear/jenkins"
            registryCredential = ‘8686ddcb-10a5-4365-b860-9bc70ab04977’
}
      // Mark the code checkout 'stage'....
        stage('Checkout the dockefile from GitHub') {
            git branch: 'master', credentialsId: 'venbollineni-7366', url: 'https://github.com/bollineni36/jenkins-pipeline-kubernetes.git'
        }
        // Build and Deploy to ACR 'stage'...
        stage('Build the Image and Push to Azure Container Registry')          {
                app = docker.build('acrncpltest.azurecr.io/helm-demo')
                withDockerRegistry([credentialsId: '8686ddcb-10a5-4365-b860-9bc70ab04977', url: 'acrncpltest.azurecr.io']) {
                app.push("${env.BUILD_NUMBER}")
                app.push('latest')
                }
        }
        stage('Build the helm charts from git code') {
              sh "helm list"
              sh "helm install --name konsear-mysql ./mysql"
        }
        stage('Delpoying the App on Azure Kubernetes Service') {
            app = docker.image('testacr.azurecr.io/demo:latest')
            withDockerRegistry([credentialsId: 'acr_credentials', url: 'acrncpltest.azurecr.io']) {
            app.pull()
            sh "kubectl create -f konsear-apache.yaml"
            sh "kubectl create -f konsear-apache_svc.yaml"
            }
        }
    }
