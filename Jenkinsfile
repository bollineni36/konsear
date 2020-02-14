node{
  def Namespace = "default"
  def repository = "acrncpltest.azurecr.io"
  def ImageName = "jenkins/php"
  def imageTag = "v1"
  
  stage('Checkout'){
      checkout([$class: 'GitSCM', branches: [[name: '*/master']], doGenerateSubmoduleConfigurations: false, extensions: [], submoduleCfg: [], userRemoteConfigs: [[credentialsId: 'venbollineni-7366', url: 'https://github.com/bollineni36/php5.6.git']]])
      
  }
  stage('Docker Build, Push'){
      sh "docker build -t $repository/${ImageName}:${imageTag} ."
      sh "docker login acrncpltest.azurecr.io "
      sh label: '', script: 'sleep 10'
      sh "docker push $repository/${ImageName}:${imageTag}"
      
      
    }
    stage('Creating the Helm charts'){
        sh "helm create konsearapp"
        sh label: '', script: 'sleep 10'
        sh "sed -i 's,nginx,'acrncpltest.azurecr.io/jenkins/php',g' < ./konsearapp/values.yaml ./konsearapp/values.yaml"
        sh "sed -i 's,stable,'v1',g' < ./konsear_app/values.yaml ./konsearapp/values.yaml"
        sh "sed -i 's,ClusterIP,'LoadBalancer',g' < ./konsearapp/values.yaml ./konsearapp/values.yaml"
        
    }
    stage('Deploying the helm charts'){
        sh "helm install konsearapp"
    }
}


