#include <stdio.h>

int main(void)
{
	char str1[] = "To be or not to be";
	char str2[] = ",that is the question";
	
	int count = 0;
	while (str1[count] != '\0') {
		count++;	
	}
	printf("\nThe length of the string \"%s\" is %d characters.", str1, count);
	count = 0;
	while (str2[count] != '\0') {
		count++;	
	}
	printf("\nThe length of the string \"%s\" is %d characters.", str2, count);
	return 0;	
}
