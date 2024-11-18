
pipeline {
    agent any

    environment {
        // Nombre del servidor SonarQube configurado en Jenkins
        SONARQUBE_SERVER = 'SonarQube_Local'
        // Agregar sonar-scanner al PATH
        PATH = "/opt/sonar-scanner/bin:${env.PATH}"
    }

    stages {
        stage('Checkout') {
            steps {
                // Clonar el código fuente desde el repositorio
                checkout scm
            }
        }
        stage('SonarQube Analysis') {
            steps {
                // Configurar el entorno de SonarQube
                withSonarQubeEnv("${SONARQUBE_SERVER}") {
                    // Ejecutar el análisis con SonarScanner
                    sh "${tool 'SonarQube_JenkinsLocal'}/bin/sonar-scanner -Dsonar.projectName=Pokedex_Grupo7 -Dsonar.projectKey=pokedex_grupo7 -Dsonar.sources=. -Dsonar.php.version=8.0"
                }
            }
        }
        stage('Quality Gate') {
            steps {
                // Esperar el resultado del Quality Gate
                timeout(time: 1, unit: 'HOURS') {
                    waitForQualityGate abortPipeline: true
                }
            }
        }

         stage('Deploy to Web Server') {
            steps {
                // Usar credenciales SSH para conectarse al servidor web
                sshagent(['webserver_ssh_credentials_id']) {
                    sh '''
                        # Asegurarse de que el servidor SSH es confiable y no pregunte por la clave
                        ssh-keyscan -H 10.30.212.72 >> ~/.ssh/known_hosts
                        ssh grupo7@10.30.212.72 'cd /var/www/html/  && git clone https://github.com/o-miralles/Pokedex-Grupo7.git || cd /var/www/html/Pokedex-Grupo7 && git pull'
                    '''
                }
            }
        }
        
        stage('DAST con OWASP ZAP') {
            steps {
                script {
                    // Remove any existing container named 'zap_scan'
                    sh '''
                    docker rm -f zap_scan || true
                    '''

                    // Run OWASP ZAP container without mounting volumes and without '--rm'
                    sh '''
                    docker run --user root --name zap_scan -v zap_volume:/zap/wrk/ -t ghcr.io/zaproxy/zaproxy:stable \
                    zap-baseline.py -t http://10.30.212.72 \
                    -r reporte_zap.html -I
                    '''

                    // Copy the report directly from the 'zap_scan' container to the Jenkins workspace
                    sh '''
                    docker cp zap_scan:/zap/wrk/reporte_zap.html ./reporte_zap.html
                    '''

                    // Remove the 'zap_scan' container
                    sh '''
                    docker rm zap_scan
                    '''
                }
            }
            post {
                always {
                    archiveArtifacts artifacts: 'reporte_zap.html', fingerprint: true
                }
            }
        }
    }
}
